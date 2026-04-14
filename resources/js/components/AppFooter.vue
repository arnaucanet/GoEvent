<template>
  <footer class="bg-slate-900 dark:bg-slate-950 text-slate-400 dark:text-slate-300 border-t border-slate-700 dark:border-slate-800">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        
        <!-- Column 1: Branding + Social -->
        <div class="lg:col-span-1">
          <div class="mb-6">
            <h3 class="text-white font-bold text-lg mb-2">GoEvent</h3>
            <p class="text-sm text-slate-500 mb-4">Let's Connect</p>
          </div>
          
          <!-- Social Icons -->
          <div class="flex gap-4    ">
            <a href="#" class="text-slate-400 hover:text-blue-500 transition-colors duration-300">
              <i class="pi pi-facebook text-xl"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-blue-500 transition-colors duration-300">
              <i class="pi pi-twitter text-xl"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-blue-500 transition-colors duration-300">
              <i class="pi pi-camera text-xl"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-blue-500 transition-colors duration-300">
              <i class="pi pi-play text-xl"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-blue-500 transition-colors duration-300">
              <i class="pi pi-linkedin text-xl"></i>
            </a>
          </div>
        </div>

        <!-- Column 2: Descarga la App -->
        <div>
          <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Descarga nuestra App</h4>
          <div class="space-y-3">
            <a href="#" class="flex items-center gap-3 px-3 py-2 bg-slate-800 dark:bg-slate-800 rounded hover:bg-blue-600 transition-colors duration-300">
              <i class="pi pi-apple text-lg"></i>
              <span class="text-xs">App Store</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 bg-slate-800 dark:bg-slate-800 rounded hover:bg-blue-600 transition-colors duration-300">
              <i class="pi pi-android text-lg"></i>
              <span class="text-xs">Google Play</span>
            </a>
          </div>
        </div>

        <!-- Column 3: Links Útiles -->
        <div>
          <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Ayuda</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Preguntas Frecuentes</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Mis Entradas</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Mi Cuenta</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Contacto</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Tarjetas Regalo</a></li>
          </ul>
        </div>


        <!-- Column 5: Empresa -->
        <div>
          <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Empresa</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Sobre Nosotros</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Carreras</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Partners</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Colaboradores</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Prensa</a></li>
          </ul>
        </div>

        <!-- Column 6: Políticas -->
        <div>
          <h4 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Políticas</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Privacidad</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Términos y Condiciones</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Política de Cookies</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Gestión de Preferencias</a></li>
            <li><a href="#" class="hover:text-blue-500 transition-colors duration-300">Seguridad</a></li>
          </ul>
        </div>

      </div>

      <!-- Divider -->
      <div class="border-t border-slate-700 dark:border-slate-800 mb-8"></div>

      <!-- Legal Section -->
      <div class="space-y-4">
        <p class="text-xs text-slate-500">
          Al continuar usando esta plataforma, aceptas nuestros 
          <a href="#" class="text-blue-500 hover:text-blue-400 transition-colors duration-300">términos de uso</a>,
          <a href="#" class="text-blue-500 hover:text-blue-400 transition-colors duration-300">política de privacidad</a> y 
          <a href="#" class="text-blue-500 hover:text-blue-400 transition-colors duration-300">política de cookies</a>.
        </p>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-slate-700 dark:border-slate-800">
          <p class="text-xs text-slate-500">
            © 2026 GoEvent. Todos los derechos reservados.
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
// Footer component - no logic needed
</script>

<style scoped>
/* Smooth transitions for links */
a {
  transition: color 0.3s ease;
}

/* Footer background gradient effect on dark mode */
@media (prefers-color-scheme: dark) {
  footer {
    background-color: #020617;
    border-color: #1e293b;
  }
}
</style>
