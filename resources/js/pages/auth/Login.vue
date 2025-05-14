<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submitForm = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <DefaultLayout>
    <Head title="Pawn Service Login" />
    <v-container fluid class="pawn-login-bg">
      <v-row class="fill-height" justify="center" align="center">
        <v-col cols="12" sm="10" md="8" lg="6" xl="4">
          <v-form @submit.prevent="submitForm">
            <v-card 
              class="mx-auto pawn-auth-card" 
              elevation="10"
              rounded="xl"
            >
              <!-- Header with Pawn Service Branding -->
              <div class="text-center pt-8">
                <v-img
                  src="/images/pawn-logo.png"
                  max-height="80"
                  max-width="80"
                  class="mx-auto mb-4"
                  contain
                ></v-img>
                <v-card-title class="text-h4 font-weight-bold text-deep-purple-darken-3">
                  Golden Pawn Portal
                  <v-icon end color="amber-darken-3" class="ml-2">mdi-diamond-stone</v-icon>
                </v-card-title>
                <v-card-subtitle class="text-medium-emphasis mb-6">
                  Secure access to your pawn service account
                </v-card-subtitle>
              </div>

              <v-card-text class="px-8">
                <!-- Email Field -->
                <v-text-field
                  variant="outlined"
                  color="deep-purple-darken-3"
                  v-model="form.email"
                  prepend-inner-icon="mdi-email-outline"
                  label="Email Address"
                  type="email"
                  class="mb-4"
                  :rules="[v => !!v || 'Email is required', v => /.+@.+\..+/.test(v) || 'Email must be valid']"
                  hide-details="auto"
                  placeholder="your@email.com"
                  required
                ></v-text-field>

                <!-- Password Field -->
                <v-text-field
                  variant="outlined"
                  v-model="form.password"
                  color="deep-purple-darken-3"
                  prepend-inner-icon="mdi-lock-outline"
                  :append-inner-icon="showPassword ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
                  label="Password"
                  :type="showPassword ? 'text' : 'password'"
                  @click:append-inner="showPassword = !showPassword"
                  class="mb-2"
                  :rules="[v => !!v || 'Password is required', v => (v && v.length >= 8) || 'Minimum 8 characters']"
                  hide-details="auto"
                  required
                ></v-text-field>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex align-center mb-6">
                  <v-checkbox
                    color="deep-purple-darken-3"
                    label="Remember me"
                    density="comfortable"
                    hide-details
                    v-model="form.remember"
                  ></v-checkbox>
                  <v-spacer></v-spacer>
                  <InertiaLink 
                    :href="route('password.request')" 
                    class="text-caption text-deep-purple-darken-3 text-decoration-none"
                  >
                    Forgot password?
                  </InertiaLink>
                </div>

                <!-- Login Button -->
                <v-btn
                  block
                  size="large"
                  color="deep-purple-darken-3"
                  rounded="lg"
                  class="text-white font-weight-bold py-6"
                  type="submit"
                  :loading="form.processing"
                >
                  Access My Account
                  <v-icon end>mdi-login</v-icon>
                  <template v-slot:loader>
                    <v-progress-circular
                      indeterminate
                      size="24"
                      color="white"
                    ></v-progress-circular>
                  </template>
                </v-btn>

                <!-- Registration Prompt -->
                <div class="text-center mt-4">
                  <span class="text-caption text-medium-emphasis">
                    New to Golden Pawn? 
                    <InertiaLink 
                      :href="route('register')" 
                      class="text-deep-purple-darken-3 text-decoration-underline"
                    >
                      Create account
                    </InertiaLink>
                  </span>
                </div>
              </v-card-text>

              <!-- Security Footer -->
              <v-card-actions class="px-8 pb-6 justify-center">
                <v-alert
                  density="compact"
                  variant="tonal"
                  color="deep-purple-darken-3"
                  class="text-caption"
                  icon="mdi-shield-lock-outline"
                >
                  <span class="text-medium-emphasis">All transactions are secured with 256-bit encryption</span>
                </v-alert>
              </v-card-actions>
            </v-card>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </DefaultLayout>
</template>

<style scoped>

.pawn-auth-card {
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 10px 30px rgba(103, 58, 183, 0.2) !important;
}

.v-text-field :deep(.v-input__details) {
  padding-left: 12px;
}

.v-btn {
  letter-spacing: normal;
  text-transform: none;
  transition: all 0.3s ease;
}

.v-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(103, 58, 183, 0.3) !important;
}
</style>