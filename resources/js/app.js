import './bootstrap';

import Alpine from 'alpinejs';

// Chart.js global import (jika sudah terpasang via npm)
import Chart from 'chart.js/auto';
window.Chart = Chart;

window.Alpine = Alpine;

Alpine.start();
