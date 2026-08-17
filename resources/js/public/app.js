import Alpine from 'alpinejs';

Alpine.start();

async function loadCharts() {

    const chartCanvas = document.querySelector('#lineChart');

    if (!chartCanvas) return;

    const { default: Chart } = await import('chart.js/auto');

    /* Browser lifecycle integration: Chart.js is dynamically loaded and
       exposed globally so page scripts can check typeof Chart before use. */
    window.Chart = Chart;

    window.dispatchEvent(new Event('chartjs:loaded'));
}

loadCharts();