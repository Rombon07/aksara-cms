import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Dispatch alpine:init so inline scripts using document.addEventListener('alpine:init') work
document.dispatchEvent(new CustomEvent('alpine:init'));

Alpine.start();
