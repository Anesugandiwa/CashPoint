<script setup>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue';
import { ref, onMounted} from 'vue'
import { useForm } from '@inertiajs/vue3'

// initiate the state of the dialog
const isDialogOpen =ref(false)
const isEditing =ref(false)
// opening dialog

const openDialog = () =>{
    isEditing.value =false
    form.reset()
    isDialogOpen.value = true
}

// close dialog
const closeDialog = () => {
    isDialogOpen.value = false
    form.reset()
}
const form = useForm({
    name: '',
    last_name: '',
    email: '',
    phone_number:'',
    national_id:'',
    address:'',
    location:'',
    date_of_birth:'',
    password:'',
    username:'',
    preferred_language:'',
    profile_image: '',

})
const errors = ref({
    name: '',
    last_name: '',
    email: '',
    phone_number:'',
    national_id:'',
    address:'',
    location:'',
    date_of_birth:'',
    password:'',
    username:'',
    preferred_language:'',
    profile_image: '',
})
</script>
<template>
    <SuperAdminLayout>
        <v-container>
            <v-row class="mb-4">
                 <v-col>
                    <v-card elevation="1" class="pa-4">
                        <v-row align="center" justify="space-between">
                            <h1 class ="text-h6 font-weight-bold"> Staff Management</h1>
                            <v-btn color="success" @click="isDialogOpen = !isDialogOpen"> Create new Staff</v-btn>

                        </v-row>

                    </v-card>
                </v-col>
                <v-dialog v-model="isDialogOpen" max-width="800">
                    <v-card class="pa-4" elevation="2">
                        <v-card-title>
                            {{ isEditing? 'Edit Staff' : 'Add New Staff' }}
                        </v-card-title>
                        <v-card-text>
                            <v-form @submit.prevent="">
                                <v-row dense>
                                     <v-col cols="12">
                                        <h3 class="text-subtitle-1 font-weight-bold">Personal Information</h3>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.name"
                                            label="First Name"
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            :error-messages="errors.name"
                                        />

                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.last_name"
                                            label="Last Name"
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            :error-messages="errors.last_name"
                                        />

                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.email"
                                            label="Email Address"
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            :error-messages="errors.email"
                                        />

                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.phone_number"
                                            label="Phone Number"
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            :error-messages="errors.phone_number"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.national_id"
                                            label="National ID Number"
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            :error-messages="errors.national_id"
                                        />
                                    </v-col>
                                     <v-col cols="12" md="6">
                                        <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        :return-value.sync="form.date_of_birth"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="form.date_of_birth"
                                            label="Date of Birth"
                                            outlined
                                            dense
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :disabled="loading"
                                            :error-messages="errors.date_of_birth"
                                        />
                                    </template>
                                    <v-date-picker
                                            v-model="form.date_of_birth"
                                            :max="maxDate"
                                            @input="menu = false"
                                    ></v-date-picker>
                                    </v-menu>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="form.address"
                                            label="Address"
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            :error-messages="errors.address"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            
                                            
                                            label="Role"
                                            
                                            outlined
                                            dense
                                            required
                                            :disabled="loading"
                                            
                                            @change="handleRoleChange"
                                        ></v-select>
                                    </v-col>
                
                                </v-row>

                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer />
                            <v-btn color="red" @click="closeDialog" variant="flat">Cancel</v-btn>
                            <v-btn color="green" @click="submitForm" variant="flat">
                                {{ isEditing? 'Update Staff Details' : 'Save Staff' }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>

                </v-dialog>

            </v-row>
        </v-container>
    </SuperAdminLayout>

</template>