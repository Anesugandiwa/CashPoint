<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    national_id: null as File | null,
    national_id_number: '',
    password: '',
    password_confirmation: '',
    address: '',
    location: '',
    date_of_birth: '',
    terms: false
});

const nationalIdPreview = ref('');
const dateMenu = ref(false);

const handleFileUpload = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        form.national_id = input.files[0];
        nationalIdPreview.value = URL.createObjectURL(input.files[0]);
    }
};

const submit = () => {
  form.post(route('register'), {
    onSuccess: () => {
      Swal.fire({
        title: 'Success!',
        text: 'Registration completed successfully.',
        icon: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
      });
    },
    onError: () => {
      // Optionally handle errors here
    },
    onFinish: () => {
      form.reset('password', 'password_confirmation');
    },
  });
}
</script>

<template>
    <v-container class="pawn-register-container">
        <v-row justify="center" align="center" class="fill-height">
            <v-col cols="12" sm="10" md="8" lg="6">
                <v-card class="pa-8 pawn-register-card" elevation="10">
                    <div class="text-center mb-6">
                        <v-img
                            src="/images/pawn-logo.png"
                            max-height="80"
                            max-width="80"
                            class="mx-auto mb-2"
                            contain
                        ></v-img>
                        <h1 class="text-h4 font-weight-bold text-deep-purple-darken-3">Golden Pawn</h1>
                        <p class="text-subtitle-1 text-grey-darken-1">Create Your Account</p>
                    </div>

                    <v-form @submit.prevent="submit" class="mt-4">
                        <!-- Personal Information Section -->
                        <v-text-field
                            label="Full Name *"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            v-model="form.name"
                            placeholder="John Doe"
                            :error-messages="form.errors.name"
                            variant="outlined"
                            color="deep-purple-darken-3"
                            prepend-inner-icon="mdi-account"
                            class="mb-4"
                        />

                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Email Address *"
                                    type="email"
                                    required
                                    :tabindex="2"
                                    autocomplete="email"
                                    v-model="form.email"
                                    placeholder="email@example.com"
                                    :error-messages="form.errors.email"
                                    variant="outlined"
                                    color="deep-purple-darken-3"
                                    prepend-inner-icon="mdi-email"
                                    class="mb-4"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Phone Number *"
                                    type="tel"
                                    required
                                    :tabindex="3"
                                    autocomplete="tel"
                                    v-model="form.phone"
                                    placeholder="+1234567890"
                                    :error-messages="form.errors.phone"
                                    variant="outlined"
                                    color="deep-purple-darken-3"
                                    prepend-inner-icon="mdi-phone"
                                    class="mb-4"
                                />
                            </v-col>
                        </v-row>

                        <!-- National ID Section -->
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="National ID Number *"
                                    required
                                    :tabindex="4"
                                    v-model="form.national_id_number"
                                    placeholder="Your government ID number"
                                    :error-messages="form.errors.national_id_number"
                                    variant="outlined"
                                    color="deep-purple-darken-3"
                                    prepend-inner-icon="mdi-card-account-details"
                                    class="mb-4"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-menu
                                    v-model="dateMenu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                >
                                    <template v-slot:activator="{ props }">
                                        <v-text-field
                                            label="Date of Birth *"
                                            v-model="form.date_of_birth"
                                            prepend-inner-icon="mdi-calendar"
                                            readonly
                                            v-bind="props"
                                            variant="outlined"
                                            color="deep-purple-darken-3"
                                            :error-messages="form.errors.date_of_birth"
                                            class="mb-4"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="form.date_of_birth"
                                        @update:model-value="dateMenu = false"
                                        :max="new Date().toISOString().split('T')[0]"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <v-file-input
                            label="Upload National ID *"
                            accept="image/*,.pdf"
                            required
                            :tabindex="5"
                            prepend-icon="mdi-paperclip"
                            variant="outlined"
                            color="deep-purple-darken-3"
                            :error-messages="form.errors.national_id"
                            @change="handleFileUpload"
                            class="mb-4"
                        >
                            <template v-slot:selection="{ fileNames }">
                                <v-chip
                                    v-for="fileName in fileNames"
                                    :key="fileName"
                                    color="deep-purple-darken-3"
                                    label
                                    size="small"
                                    class="me-2"
                                >
                                    {{ fileName }}
                                </v-chip>
                            </template>
                        </v-file-input>

                        <v-img
                            v-if="nationalIdPreview"
                            :src="nationalIdPreview"
                            max-height="150"
                            contain
                            class="mb-4 mx-auto"
                        ></v-img>

                        <!-- Address Section -->
                        <v-text-field
                            label="Address *"
                            required
                            :tabindex="6"
                            v-model="form.address"
                            placeholder="Street, City"
                            :error-messages="form.errors.address"
                            variant="outlined"
                            color="deep-purple-darken-3"
                            prepend-inner-icon="mdi-home-map-marker"
                            class="mb-4"
                        />

                        <v-text-field
                            label="Location *"
                            required
                            :tabindex="7"
                            v-model="form.location"
                            placeholder="State/Province, Country"
                            :error-messages="form.errors.location"
                            variant="outlined"
                            color="deep-purple-darken-3"
                            prepend-inner-icon="mdi-map-marker"
                            class="mb-4"
                        />

                        <!-- Password Section -->
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Password *"
                                    type="password"
                                    required
                                    :tabindex="8"
                                    autocomplete="new-password"
                                    v-model="form.password"
                                    placeholder="Create a password"
                                    :error-messages="form.errors.password"
                                    variant="outlined"
                                    color="deep-purple-darken-3"
                                    prepend-inner-icon="mdi-lock"
                                    class="mb-4"
                                />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Confirm Password *"
                                    type="password"
                                    required
                                    :tabindex="9"
                                    autocomplete="new-password"
                                    v-model="form.password_confirmation"
                                    placeholder="Confirm your password"
                                    :error-messages="form.errors.password_confirmation"
                                    variant="outlined"
                                    color="deep-purple-darken-3"
                                    prepend-inner-icon="mdi-lock-check"
                                    class="mb-4"
                                />
                            </v-col>
                        </v-row>

                        <!-- Terms & Conditions -->
                        <v-checkbox
                            v-model="form.terms"
                            :error-messages="form.errors.terms"
                            :tabindex="10"
                            color="deep-purple-darken-3"
                            class="mt-2 mb-4"
                        >
                            <template v-slot:label>
                                <div>
                                    I agree to the 
                                    <a href="#" class="text-deep-purple-darken-3 text-decoration-underline">Terms & Conditions</a> *
                                </div>
                            </template>
                        </v-checkbox>

                        <v-btn
                            block
                            size="large"
                            type="submit"
                            color="deep-purple-darken-3"
                            :tabindex="11"
                            :loading="form.processing"
                            :disabled="!form.terms"
                            class="text-none font-weight-bold mb-4"
                            rounded="xl"
                        >
                            Create Account
                            <template v-slot:loader>
                                <v-progress-circular
                                    indeterminate
                                    size="24"
                                    color="white"
                                ></v-progress-circular>
                            </template>
                        </v-btn>

                        <v-divider class="my-4"></v-divider>

                        <div class="text-center mt-4">
                            <p class="text-body-2 text-grey-darken-1">
                                Already have an account?
                                <InertiaLink :href="route('login')">
                                    <v-btn
                                        variant="text"
                                        color="deep-purple-darken-3"
                                        :tabindex="12"
                                        class="text-none font-weight-bold ml-1"
                                    >
                                        Sign In
                                    </v-btn>
                                </InertiaLink>
                            </p>
                        </div>
                    </v-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.pawn-register-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    height: 100vh;
}

.pawn-register-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px 0 rgba(103, 58, 183, 0.15) !important;
}

.v-card-title {
    letter-spacing: 0.5px;
}

.v-btn {
    letter-spacing: normal;
    text-transform: none;
}

.password-strength {
    margin-top: -12px;
    margin-bottom: 8px;
    font-size: 0.75rem;
}

.password-weak {
    color: #f44336;
}

.password-medium {
    color: #ff9800;
}

.password-strong {
    color: #4caf50;
}

.required-field::after {
    content: " *";
    color: #f44336;
}
</style>