<template>
    <div class="auth-ticket-page min-h-[calc(100vh-5rem)] px-4 py-8 sm:px-6 lg:px-8 flex items-center">
        <div class="mx-auto grid w-full max-w-6xl overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-2xl lg:grid-cols-12">
            <aside class="hidden lg:col-span-5 lg:flex lg:flex-col lg:justify-between p-8 text-white ticket-side">
                <div>
                    <h1 class="mt-4 text-4xl font-black leading-tight">Vuelve a tus eventos en segundos.</h1>
                    <p class="mt-4 text-cyan-100/90 text-sm leading-relaxed">
                        Accede a tus entradas, favoritos y alertas de nuevas fechas desde un solo lugar.
                    </p>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="feature-chip">
                        <i class="pi pi-bolt"></i>
                        <span>Check-out rapido y seguro</span>
                    </div>
                    <div class="feature-chip">
                        <i class="pi pi-bell"></i>
                        <span>Alertas de lanzamiento</span>
                    </div>
                    <div class="feature-chip">
                        <i class="pi pi-ticket"></i>
                        <span>Entradas digitales centralizadas</span>
                    </div>
                </div>
            </aside>

            <section class="lg:col-span-7 p-6 sm:p-8 lg:p-10">
                <div class="mx-auto w-full max-w-md">
                    <div class="mb-8">
                        <h2 class="text-3xl font-black text-slate-900">Inicia sesion</h2>
                        <p class="mt-2 text-sm text-slate-600">Entra para gestionar tus eventos y compras.</p>
                    </div>

                    <form @submit.prevent="submitLogin" class="space-y-5">
                        <div class="flex flex-col gap-2">
                            <label for="email" class="text-sm font-semibold text-slate-700">{{ $t('email') }}</label>
                            <InputText
                                id="email"
                                type="email"
                                v-model="loginForm.email"
                                placeholder="tu@email.com"
                                :class="{ 'p-invalid': validationErrors?.email }"
                                class="w-full"
                            />
                            <small v-if="validationErrors?.email" class="text-red-500">
                                <div v-for="message in validationErrors.email" :key="message">{{ message }}</div>
                            </small>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-sm font-semibold text-slate-700">{{ $t('password') }}</label>
                            <Password
                                id="password"
                                v-model="loginForm.password"
                                placeholder="••••••••"
                                :toggleMask="true"
                                :feedback="false"
                                inputClass="w-full"
                                :class="{ 'p-invalid': validationErrors?.password }"
                                fluid
                            />
                            <small v-if="validationErrors?.password" class="text-red-500">
                                <div v-for="message in validationErrors.password" :key="message">{{ message }}</div>
                            </small>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <Checkbox v-model="loginForm.remember" inputId="remember" binary />
                                <label for="remember" class="text-sm cursor-pointer text-slate-700">{{ $t('remember_me') }}</label>
                            </div>
                            <router-link
                                :to="{ name: 'auth.forgot-password' }"
                                class="text-sm font-semibold text-blue-700 hover:text-blue-600 transition-colors"
                            >
                                {{ $t('forgot_password') }}
                            </router-link>
                        </div>

                        <Button
                            type="submit"
                            :label="$t('login')"
                            :loading="processing"
                            :disabled="processing"
                            class="w-full btn-ticket-primary"
                            size="large"
                        />

                        <div class="text-center rounded-xl bg-slate-50 border border-slate-200 p-3">
                            <p class="text-sm text-slate-600">
                                ¿No tienes una cuenta?
                                <router-link
                                    :to="{ name: 'auth.register' }"
                                    class="font-semibold text-blue-700 hover:text-blue-600 transition-colors"
                                >
                                    Crear cuenta
                                </router-link>
                            </p>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import useAuth from '@/composables/auth';

const { loginForm, validationErrors, processing, submitLogin } = useAuth();
</script>

<style scoped>
.auth-ticket-page {
    background:
        radial-gradient(circle at 10% 10%, rgba(14, 116, 144, 0.16), transparent 35%),
        radial-gradient(circle at 90% 20%, rgba(37, 99, 235, 0.16), transparent 28%),
        linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
}

.ticket-side {
    background:
        radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.35), transparent 35%),
        linear-gradient(155deg, #0f172a 0%, #1e3a8a 50%, #0f172a 100%);
}

.feature-chip {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    border: 1px solid rgba(186, 230, 253, 0.35);
    background: rgba(8, 47, 73, 0.35);
    border-radius: 0.85rem;
    padding: 0.65rem 0.8rem;
}

:deep(.btn-ticket-primary.p-button) {
    background: linear-gradient(90deg, #0ea5e9 0%, #2563eb 100%);
    border: 1px solid #2563eb;
    color: #ffffff;
    border-radius: 10px;
    font-weight: 700;
}

:deep(.btn-ticket-primary.p-button:hover) {
    filter: brightness(1.05);
}
</style>
