import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;
Alpine.start();

window.toggleDarkMode = () => {
    document.documentElement.classList.toggle('dark');
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('appearance', isDark ? 'dark' : 'light');
}
