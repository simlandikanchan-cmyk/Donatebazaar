import '../css/app.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

async function loadCharts() {

    const chartCanvas = document.querySelector('#lineChart');

    if (!chartCanvas) return;

    const { default: Chart } = await import('chart.js/auto');

    window.Chart = Chart;

    window.dispatchEvent(new Event('chartjs:loaded'));
}

loadCharts();