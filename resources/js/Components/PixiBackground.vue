<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import * as PIXI from 'pixi.js';

const canvasContainer = ref(null);
let app = null;

const PARTICLE_COUNT = 150;
const particles = [];

onMounted(async () => {
    // Initialize PixiJS Application
    app = new PIXI.Application();
    
    await app.init({
        resizeTo: window,
        backgroundAlpha: 0,
        antialias: true,
        resolution: window.devicePixelRatio || 1,
    });

    canvasContainer.value.appendChild(app.canvas);

    // Create a simple particle texture (a soft circle)
    const graphics = new PIXI.Graphics()
        .circle(0, 0, 4)
        .fill({ color: 0xffffff, alpha: 0.3 });
    
    const texture = app.renderer.generateTexture({
        target: graphics,
        resolution: 1,
        antialias: false
    });

    // Create a Container for particles
    const container = new PIXI.Container();
    app.stage.addChild(container);

    // Initialize particles
    for (let i = 0; i < PARTICLE_COUNT; i++) {
        const particle = new PIXI.Sprite(texture);
        resetParticle(particle, true);
        container.addChild(particle);
        particles.push(particle);
    }

    // Animation Loop
    app.ticker.add((ticker) => {
        const delta = ticker.deltaTime;
        
        particles.forEach(p => {
            p.y -= p.speed * delta;
            p.x += Math.sin(p.angle) * 0.5;
            p.angle += 0.01;
            p.alpha -= 0.001 * delta;

            if (p.y < -10 || p.alpha <= 0) {
                resetParticle(p);
            }
        });
    });
});

function resetParticle(p, isInitial = false) {
    p.x = Math.random() * window.innerWidth;
    p.y = isInitial ? Math.random() * window.innerHeight : window.innerHeight + 10;
    p.speed = 0.2 + Math.random() * 0.5;
    p.scale.set(0.5 + Math.random() * 1.5);
    p.alpha = 0.1 + Math.random() * 0.4;
    p.angle = Math.random() * Math.PI * 2;
}

onUnmounted(() => {
    if (app) {
        app.destroy(true, { children: true, texture: true });
    }
});
</script>

<template>
    <div ref="canvasContainer" class="fixed inset-0 pointer-events-none z-0"></div>
</template>

<style scoped>
canvas {
    display: block;
}
</style>
