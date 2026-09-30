<script setup lang="ts">
    import { useForm } from '@inertiajs/vue3';
    import { Head } from '@inertiajs/vue3';

    const form = useForm({
    username: '',
    password: '',
    remember: false,
    });

    const submit = () => {
        form.post('login', {
            onFinish: () => form.reset('password'),
        });
    };

</script>

<template>
  <Head title="Log in" />

  <main class="min-h-screen w-full bg-gray-100 flex justify-center items-center p-4">

    <!-- Login Card Container -->
    <div class="w-full max-w-md bg-white border border-stone-200 shadow-md p-8 space-y-6">

      <!-- Header / Logo -->
      <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2">
          <span class="text-2xl font-bold tracking-tight text-emerald-950">Property Tracker</span>
          <span class="text-[10px] font-semibold tracking-wider bg-emerald-900 text-emerald-100 px-2 py-0.5">
            v 1.0
          </span>
        </div>
        <p class="text-xs font-medium text-stone-500">Property Management System</p>
        <h1 class="text-lg font-bold text-stone-900 pt-2">Sign in</h1>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="submit" class="space-y-4">

        <!-- Username Field -->
        <div class="space-y-1.5">
          <label for="username" class="block text-xs font-semibold uppercase">
            Username
          </label>
          <input
            id="username"
            v-model="form.username"
            type="text"
            required
            autofocus
            placeholder="Enter your username"
            class="w-full p-2 border border-stone-300 text-sm text-stone-800 outline-none tracking-tight"
          />
          <p v-if="form.errors.username" class="text-xs text-red-600 font-medium">
            {{ form.errors.username }}
          </p>
        </div>

        <!-- Password Field -->
        <div class="space-y-1.5">
          <label for="password" class="block text-xs font-semibold uppercase">
            Password
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            placeholder="Enter your password"
            class="w-full p-2 border border-stone-300 text-sm text-stone-800 outline-none tracking-tight"
          />
          <p v-if="form.errors.password" class="text-xs text-red-600 font-medium">
            {{ form.errors.password }}
          </p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full p-2 uppercase text-sm font-semibold tracking-wide bg-emerald-900 text-white hover:bg-emerald-800 transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
        >
          {{ form.processing ? 'Signing in...' : 'Login' }}
        </button>

      </form>

      <!-- Bottom Footer -->
      <div class="pt-4 border-t border-stone-100 text-center">
        <p class="text-xs text-stone-400">
          Need help? Contact system administrator
        </p>
      </div>

    </div>

  </main>
</template>