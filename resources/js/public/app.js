import Alpine from 'alpinejs';

Alpine.start();

async function loadCharts() {

    const chartCanvas = document.querySelector('#lineChart');

    if (!chartCanvas) return;

    const { default: Chart } = await import('chart.js/auto');

    window.Chart = Chart;

    window.dispatchEvent(new Event('chartjs:loaded'));
}

loadCharts();