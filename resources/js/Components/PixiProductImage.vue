<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import * as PIXI from 'pixi.js';

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    alt: {
        type: String,
        default: ''
    }
});

const canvasContainer = ref(null);
const hasError = ref(false);
const isLoading = ref(true);

let app = null;
let sprite = null;
let displacementSprite = null;
let displacementFilter = null;
let isHovered = false;

const resize = () => {
    if (!app || !app.renderer || !canvasContainer.value || !sprite) return;
    
    const parent = canvasContainer.value;
    if (parent && parent.clientWidth && parent.clientHeight) {
        app.renderer.resize(parent.clientWidth, parent.clientHeight);
        sprite.x = app.screen.width / 2;
        sprite.y = app.screen.height / 2;
        
        // Cover logic
        const texture = sprite.texture;
        if (texture && texture.width && texture.height) {
            const ratio = Math.max(app.screen.width / texture.width, app.screen.height / texture.height);
            sprite.scale.set(ratio);
        }
    }
};

onMounted(async () => {
    try {
        app = new PIXI.Application();
        
        await app.init({
            preference: 'webgl',
            autoDensity: true,
            backgroundAlpha: 0,
            antialias: true,
            resolution: window.devicePixelRatio || 1,
        });

        if (!canvasContainer.value) return;
        canvasContainer.value.appendChild(app.canvas);

        // Load texture with error handling
        const texture = await PIXI.Assets.load(props.src).catch(err => {
            console.error('Pixi asset load failed:', props.src, err);
            throw err;
        });

        // Use a much simpler displacement texture generation
        const noiseSize = 512;
        const noiseGraphics = new PIXI.Graphics();
        for (let i = 0; i < 40; i++) {
            const x = Math.random() * noiseSize;
            const y = Math.random() * noiseSize;
            const radius = 20 + Math.random() * 60;
            noiseGraphics.circle(x, y, radius).fill({ color: 0xffffff, alpha: 0.5 });
        }
        
        const noiseTexture = app.renderer.generateTexture({
            target: noiseGraphics,
            resolution: 1,
        });

        if (!noiseTexture) throw new Error('Noise texture generation failed');

        sprite = new PIXI.Sprite(texture);
        sprite.anchor.set(0.5);
        
        displacementSprite = new PIXI.TilingSprite({
            texture: noiseTexture,
            width: noiseSize,
            height: noiseSize
        });
        
        // In PixiJS v8, DisplacementFilter is often part of PIXI.filters
        // and the constructor usually takes the sprite directly if it's there.
        // Let's use the most basic form that works in v8.
        const DisplacementFilter = PIXI.DisplacementFilter;
        if (!DisplacementFilter) throw new Error('DisplacementFilter not found in PIXI');

        displacementFilter = new DisplacementFilter({
            sprite: displacementSprite,
            scale: { x: 0, y: 0 }
        });

        app.stage.addChild(sprite);
        app.stage.addChild(displacementSprite);
        sprite.filters = [displacementFilter];

        window.addEventListener('resize', resize);
        resize();

        app.ticker.add((ticker) => {
            if (!displacementSprite || !displacementFilter) return;
            
            displacementSprite.tilePosition.x += 0.4 * ticker.deltaTime;
            displacementSprite.tilePosition.y += 0.4 * ticker.deltaTime;

            const targetScale = isHovered ? 20 : 0;
            displacementFilter.scale.x += (targetScale - displacementFilter.scale.x) * 0.1 * ticker.deltaTime;
            displacementFilter.scale.y += (targetScale - displacementFilter.scale.y) * 0.1 * ticker.deltaTime;
        });

        isLoading.value = false;
    } catch (err) {
        console.error('Pixi execution failed for', props.src, ':', err);
        hasError.value = true;
        isLoading.value = false;
        if (app) {
            app.destroy(true, { children: true, texture: true });
            app = null;
        }
    }
});

const onMouseEnter = () => { isHovered = true; };
const onMouseLeave = () => { isHovered = false; };

onUnmounted(() => {
    window.removeEventListener('resize', resize);
    if (app) {
        app.destroy(true, { children: true, texture: true });
        app = null;
    }
});
</script>

<template>
    <div 
        class="relative w-full h-full cursor-pointer overflow-hidden bg-gray-900"
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
    >
        <!-- Pixi Canvas Container -->
        <div v-if="!hasError" ref="canvasContainer" class="w-full h-full"></div>
        
        <!-- Fallback/Static Image -->
        <img 
            v-if="hasError || isLoading"
            :src="src" 
            :alt="alt" 
            class="w-full h-full object-cover transition-opacity duration-500"
            :class="{ 'opacity-0': !hasError && isLoading, 'opacity-100': hasError }"
        />
    </div>
</template>

<style scoped>
canvas {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
