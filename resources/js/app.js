import "./bootstrap";

import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

// Import SweetAlert2
import Swal from "sweetalert2";
window.Swal = Swal;

// Import jQuery
import $ from "jquery";
window.$ = window.jQuery = $;

// Import SortableJS
import Sortable from "sortablejs";
window.Sortable = Sortable;

// Import Canvas Confetti
import confetti from "canvas-confetti";
window.confetti = confetti;

// Dispatch event when all libraries are loaded
document.dispatchEvent(new Event("vite:loaded"));
