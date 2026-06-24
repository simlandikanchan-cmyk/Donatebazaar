// Import Tailwind CSS
import '../css/app.css';

// Page CSS
import '../css/home.css';
import '../css/about.css';
import '../css/contact.css';

// Page JS
import './home.js';
import './about.js';
import './contact.js';

// Alpine.js
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Lazy load Chart.js ONLY when needed
async function loadCharts() {

    const chartCanvas = document.querySelector('#lineChart');

    if (!chartCanvas) return;

    const { default: Chart } = await import('chart.js/auto');

    window.Chart = Chart;

    window.dispatchEvent(new Event('chartjs:loaded'));
}

loadCharts();