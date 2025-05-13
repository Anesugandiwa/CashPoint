<template>
  <SuperAdminLayout>
    <v-container fluid>
      <v-card>
        <v-card-title class="headline primary white--text">
          Create New Loan
        </v-card-title>
        
        <v-stepper v-model="currentStep" class="elevation-0">
          <v-stepper-header>
            <v-stepper-step :complete="currentStep > 1" step="1">
              Customer Information
            </v-stepper-step>
            
            <v-divider></v-divider>
            
            <v-stepper-step :complete="currentStep > 2" step="2">
              Asset Information
            </v-stepper-step>
            
            <v-divider></v-divider>
            
            <v-stepper-step :complete="currentStep > 3" step="3">
              Loan Details
            </v-stepper-step>
            
            <v-divider></v-divider>
            
            <v-stepper-step step="4">
              Review & Submit
            </v-stepper-step>
          </v-stepper-header>
          
          <v-stepper-items>
            <!-- Step 1: Customer Information -->
            <v-stepper-content step="1">
              <v-form ref="step1Form" v-model="step1Valid" lazy-validation>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="customer.full_name"
                      label="Full Name"
                      outlined
                      :rules="nameRules"
                      required
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="customer.email"
                      label="Email Address"
                      outlined
                      :rules="emailRules"
                      required
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="customer.phone_number"
                      label="Phone Number"
                      outlined
                      :rules="phoneRules"
                      required
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="customer.national_id"
                      label="National ID"
                      outlined
                      :rules="idRules"
                      required
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-select
                      v-model="customer.location"
                      :items="locationOptions"
                      label="Location"
                      outlined
                      :rules="requiredRule"
                      required
                    ></v-select>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="customer.address"
                      label="Address"
                      outlined
                      :rules="requiredRule"
                      required
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-form>
              
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  @click="validateStep1"
                >
                  Continue
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </v-card-actions>
            </v-stepper-content>
            
            <!-- Step 2: Asset Information -->
            <v-stepper-content step="2">
              <v-form ref="step2Form" v-model="step2Valid" lazy-validation>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-select
                      v-model="asset.asset_type_id"
                      :items="assetTypes"
                      item-text="name"
                      item-value="id"
                      label="Asset Type"
                      outlined
                      :rules="requiredRule"
                      required
                      @change="handleAssetTypeChange"
                    ></v-select>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="asset.title"
                      label="Asset Title"
                      outlined
                      :rules="requiredRule"
                      required
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12">
                    <v-textarea
                      v-model="asset.description"
                      label="Asset Description"
                      outlined
                      :rules="requiredRule"
                      required
                      rows="3"
                    ></v-textarea>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-select
                      v-model="asset.condition"
                      :items="['New', 'Like New', 'Used', 'Damaged']"
                      label="Condition"
                      outlined
                      :rules="requiredRule"
                      required
                    ></v-select>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="asset.year_of_purchase"
                      label="Year of Purchase"
                      type="number"
                      :min="1900"
                      :max="new Date().getFullYear()"
                      outlined
                    ></v-text-field>
                  </v-col>
                </v-row>
                
                <!-- Vehicle Details (conditional) -->
                <template v-if="isVehicle">
                  <v-row>
                    <v-col cols="12">
                      <h3 class="subtitle-1 mt-2">Vehicle Details</h3>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="vehicle.make"
                        label="Make"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="vehicle.model"
                        label="Model"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="vehicle.registration_number"
                        label="Registration Number"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-text-field
                        v-model="vehicle.mileage"
                        label="Mileage"
                        type="number"
                        min="0"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="vehicle.color"
                        label="Color"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="vehicle.engine_number"
                        label="Engine Number"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="4">
                      <v-text-field
                        v-model="vehicle.chassis_number"
                        label="Chassis Number"
                        outlined
                        :rules="requiredRule"
                        required
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </template>
              </v-form>
              
              <v-card-actions>
                <v-btn
                  color="secondary"
                  text
                  @click="currentStep--"
                >
                  <v-icon left>mdi-arrow-left</v-icon>
                  Back
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  @click="validateStep2"
                >
                  Continue
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </v-card-actions>
            </v-stepper-content>
            
            <!-- Step 3: Loan Details -->
            <v-stepper-content step="3">
              <v-form ref="step3Form" v-model="step3Valid" lazy-validation>
                <v-row>
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="valuation.market_value"
                      label="Market Value"
                      type="number"
                      min="0"
                      prefix="$"
                      outlined
                      :rules="requiredRule"
                      required
                      @input="calculateValues"
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="valuation.final_evaluation_value"
                      label="Final Evaluation Value"
                      type="number"
                      min="0"
                      prefix="$"
                      outlined
                      :rules="requiredRule"
                      required
                      @input="calculateLoanValue"
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="valuation.final_loan_value"
                      label="Maximum Loan Value"
                      type="number"
                      min="0"
                      prefix="$"
                      outlined
                      :rules="requiredRule"
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.principal_amount"
                      label="Loan Amount"
                      type="number"
                      min="0"
                      :max="valuation.final_loan_value"
                      prefix="$"
                      outlined
                      :rules="[
                        v => !!v || 'Loan amount is required',
                        v => Number(v) <= Number(valuation.final_loan_value) || 'Cannot exceed maximum loan value'
                      ]"
                      required
                      @input="calculateTotalAmount"
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.interest_rate"
                      label="Interest Rate"
                      type="number"
                      min="0"
                      suffix="%"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.storage_charge_percentage"
                      label="Storage Charge"
                      type="number"
                      min="0"
                      suffix="%"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.loan_period_days"
                      label="Loan Period (Days)"
                      type="number"
                      min="1"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.interest_amount"
                      label="Interest Amount"
                      prefix="$"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.storage_charge_amount"
                      label="Storage Charge Amount"
                      prefix="$"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.start_date"
                      label="Start Date"
                      type="date"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.due_date"
                      label="Due Date"
                      type="date"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="4">
                    <v-text-field
                      v-model="loan.total_amount_due"
                      label="Total Amount Due"
                      prefix="$"
                      outlined
                      readonly
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-select
                      v-model="loan.disbursement_method"
                      :items="disbursementMethods"
                      label="Disbursement Method"
                      outlined
                      :rules="requiredRule"
                      required
                    ></v-select>
                  </v-col>
                  
                  <v-col cols="12" md="6">
                    <v-text-field
                      v-model="loan.disbursement_reference"
                      label="Disbursement Reference"
                      outlined
                      :rules="requiredRule"
                      required
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="12">
                    <v-textarea
                      v-model="loan.notes"
                      label="Loan Notes"
                      outlined
                      rows="3"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-form>
              
              <v-card-actions>
                <v-btn
                  color="secondary"
                  text
                  @click="currentStep--"
                >
                  <v-icon left>mdi-arrow-left</v-icon>
                  Back
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  @click="validateStep3"
                >
                  Review Application
                  <v-icon right>mdi-eye</v-icon>
                </v-btn>
              </v-card-actions>
            </v-stepper-content>
            
            <!-- Step 4: Review & Submit -->
            <v-stepper-content step="4">
              <v-card flat>
                <v-card-title class="text-h6">Review Your Application</v-card-title>
                
                <!-- Customer Information Review -->
                <v-card-text>
                  <v-list dense>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title class="text-subtitle-1 font-weight-bold">Customer Information</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Full Name</v-list-item-title>
                        <v-list-item-subtitle>{{ customer.full_name }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Email</v-list-item-title>
                        <v-list-item-subtitle>{{ customer.email }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Phone</v-list-item-title>
                        <v-list-item-subtitle>{{ customer.phone_number }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>National ID</v-list-item-title>
                        <v-list-item-subtitle>{{ customer.national_id }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Location</v-list-item-title>
                        <v-list-item-subtitle>{{ customer.location }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Address</v-list-item-title>
                        <v-list-item-subtitle>{{ customer.address }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
                  
                  <!-- Asset Information Review -->
                  <v-list dense class="mt-4">
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title class="text-subtitle-1 font-weight-bold">Asset Information</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Asset Type</v-list-item-title>
                        <v-list-item-subtitle>
                          {{ assetTypes.find(t => t.id === asset.asset_type_id)?.name }}
                        </v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Asset Title</v-list-item-title>
                        <v-list-item-subtitle>{{ asset.title }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Condition</v-list-item-title>
                        <v-list-item-subtitle>{{ asset.condition }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Market Value</v-list-item-title>
                        <v-list-item-subtitle>${{ valuation.market_value }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Final Evaluation</v-list-item-title>
                        <v-list-item-subtitle>${{ valuation.final_evaluation_value }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Max Loan Value</v-list-item-title>
                        <v-list-item-subtitle>${{ valuation.final_loan_value }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <template v-if="isVehicle">
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Vehicle Make/Model</v-list-item-title>
                          <v-list-item-subtitle>{{ vehicle.make }} {{ vehicle.model }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Registration</v-list-item-title>
                          <v-list-item-subtitle>{{ vehicle.registration_number }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-list>
                  
                  <!-- Loan Details Review -->
                  <v-list dense class="mt-4">
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title class="text-subtitle-1 font-weight-bold">Loan Details</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Loan Amount</v-list-item-title>
                        <v-list-item-subtitle>${{ loan.principal_amount }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Interest Rate</v-list-item-title>
                        <v-list-item-subtitle>{{ loan.interest_rate }}%</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Loan Period</v-list-item-title>
                        <v-list-item-subtitle>{{ loan.loan_period_days }} days</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Total Amount Due</v-list-item-title>
                        <v-list-item-subtitle>${{ loan.total_amount_due }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Due Date</v-list-item-title>
                        <v-list-item-subtitle>{{ formatDate(loan.due_date) }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title>Disbursement Method</v-list-item-title>
                        <v-list-item-subtitle>{{ loan.disbursement_method }}</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
                </v-card-text>
              </v-card>
              
              <v-card-actions>
                <v-btn
                  color="secondary"
                  text
                  @click="currentStep--"
                >
                  <v-icon left>mdi-arrow-left</v-icon>
                  Back
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  :loading="isSubmitting"
                  @click="submitLoan"
                >
                  Submit Application
                  <v-icon right>mdi-check</v-icon>
                </v-btn>
              </v-card-actions>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-card>
      
      <!-- Success Dialog -->
      <v-dialog v-model="successDialog" max-width="500px" persistent>
        <v-card>
          <v-card-title class="headline success white--text">
            <v-icon left color="white">mdi-check-circle</v-icon>
            Loan Created Successfully
          </v-card-title>
          
          <v-card-text class="pt-4">
            <p>The loan has been created successfully with the following details:</p>
            
            <v-list dense>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Loan Number</v-list-item-title>
                  <v-list-item-subtitle>{{ createdLoan.loan_number }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Customer</v-list-item-title>
                  <v-list-item-subtitle>{{ createdLoan.customer_name }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Asset</v-list-item-title>
                  <v-list-item-subtitle>{{ createdLoan.asset_title }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Loan Amount</v-list-item-title>
                  <v-list-item-subtitle>${{ formatNumber(createdLoan.principal_amount) }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Due Date</v-list-item-title>
                  <v-list-item-subtitle>{{ formatDate(createdLoan.due_date) }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
          
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              @click="goToLoanDetails"
            >
              View Loan Details
            </v-btn>
            <v-btn
              color="primary"
              @click="createAnotherLoan"
            >
              Create Another Loan
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </SuperAdminLayout>
</template>

<script>
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue';

export default {
  name: 'MultiStepLoanCreate',
  components: {
    SuperAdminLayout
  },
  data() {
    return {
      currentStep: 1,
      step1Valid: false,
      step2Valid: false,
      step3Valid: false,
      isSubmitting: false,
      
      // Customer Information
      customer: {
        full_name: '',
        email: '',
        phone_number: '',
        national_id: '',
        location: '',
        address: '',
      },
      
      // Asset Information
      asset: {
        asset_type_id: null,
        title: '',
        description: '',
        condition: '',
        year_of_purchase: new Date().getFullYear(),
      },
      
      // Vehicle Details
      vehicle: {
        make: '',
        model: '',
        registration_number: '',
        mileage: '',
        color: '',
        engine_number: '',
        chassis_number: '',
      },
      
      // Asset Valuation
      valuation: {
        market_value: '',
        final_evaluation_value: '',
        final_loan_value: '',
      },
      
      // Loan Information
      loan: {
        principal_amount: '',
        interest_rate: '',
        loan_period_days: '',
        storage_charge_percentage: '',
        interest_amount: '',
        storage_charge_amount: '',
        total_amount_due: '',
        start_date: new Date().toISOString().split('T')[0],
        due_date: '',
        disbursement_method: '',
        disbursement_reference: '',
        notes: '',
      },
      
      // Success Dialog
      successDialog: false,
      createdLoan: {
        id: null,
        loan_number: '',
        customer_name: '',
        asset_title: '',
        principal_amount: 0,
        due_date: '',
      },
      
      // Options
      locationOptions: [
        'Harare', 'Bulawayo', 'Mutare', 'Gweru', 'Kwekwe', 
        'Kadoma', 'Masvingo', 'Chinhoyi', 'Marondera', 'Bindura'
      ],
      assetTypes: [
        { id: 1, name: 'Vehicle', loan_percentage: 50 },
        { id: 2, name: 'Jewelry', loan_percentage: 30 },
        { id: 3, name: 'Electronics', loan_percentage: 30 },
        { id: 4, name: 'Collectibles', loan_percentage: 30 },
        { id: 5, name: 'Furniture', loan_percentage: 30 },
      ],
      disbursementMethods: [
        'Cash', 'Bank Transfer', 'Mobile Money', 'Check', 'Credit Account'
      ],
      
      // Validation Rules
      requiredRule: [v => !!v || 'This field is required'],
      nameRules: [
        v => !!v || 'Name is required',
        v => v.length >= 3 || 'Name must be at least 3 characters'
      ],
      emailRules: [
        v => !!v || 'Email is required',
        v => /.+@.+\..+/.test(v) || 'Email must be valid'
      ],
      phoneRules: [
        v => !!v || 'Phone number is required',
        v => /^\d{10,}$/.test(v.replace(/\D/g, '')) || 'Phone must be valid'
      ],
      idRules: [
        v => !!v || 'National ID is required'
      ],
    };
  },
  
  computed: {
    isVehicle() {
      return this.asset.asset_type_id === 1; // Vehicle type ID
    },
    
    selectedAssetType() {
      if (!this.asset.asset_type_id) return null;
      return this.assetTypes.find(type => type.id === this.asset.asset_type_id);
    }
  },
  
  methods: {
    validateStep1() {
      if (this.$refs.step1Form.validate()) {
        this.currentStep++;
      }
    },
    
    validateStep2() {
      if (this.$refs.step2Form.validate()) {
        this.currentStep++;
      }
    },
    
    validateStep3() {
      if (this.$refs.step3Form.validate()) {
        this.currentStep++;
      }
    },
    
    handleAssetTypeChange() {
      // Set default loan terms based on asset type
      if (this.isVehicle) {
        this.loan.interest_rate = 4;
        this.loan.loan_period_days = 30;
        this.loan.storage_charge_percentage = 21;
      } else {
        this.loan.interest_rate = 2;
        this.loan.loan_period_days = 14;
        this.loan.storage_charge_percentage = 18;
      }
      
      // Recalculate values if already present
      if (this.valuation.market_value) {
        this.calculateValues();
      }
    },
    
    calculateValues() {
      if (!this.valuation.market_value || !this.selectedAssetType) return;
      
      // Set final evaluation equal to market value for simplicity
      this.valuation.final_evaluation_value = this.valuation.market_value;
      this.calculateLoanValue();
    },
    
    calculateLoanValue() {
      if (!this.valuation.final_evaluation_value || !this.selectedAssetType) return;
      
      // Calculate loan value as percentage of evaluation value
      const loanPercentage = this.selectedAssetType.loan_percentage / 100;
      this.valuation.final_loan_value = (this.valuation.final_evaluation_value * loanPercentage).toFixed(2);
      
      // Set initial loan amount suggestion
      this.loan.principal_amount = this.valuation.final_loan_value;
      
      // Calculate total amount
      this.calculateTotalAmount();
    },
    
    calculateTotalAmount() {
      if (!this.loan.principal_amount) return;
      
      const principal = parseFloat(this.loan.principal_amount);
      const interestRate = parseFloat(this.loan.interest_rate) / 100;
      const storageFeeRate = parseFloat(this.loan.storage_charge_percentage) / 100;
      const periodInYears = parseFloat(this.loan.loan_period_days) / 365;
      
      // Calculate interest amount
      this.loan.interest_amount = (principal * interestRate * periodInYears).toFixed(2);
      
      // Calculate storage charge
      this.loan.storage_charge_amount = (principal * storageFeeRate).toFixed(2);
      
      // Calculate total amount due
      this.loan.total_amount_due = (
        principal + 
        parseFloat(this.loan.interest_amount) + 
        parseFloat(this.loan.storage_charge_amount)
      ).toFixed(2);
      
      // Calculate due date
      const startDate = new Date(this.loan.start_date);
      const dueDate = new Date(startDate);
      dueDate.setDate(dueDate.getDate() + parseInt(this.loan.loan_period_days));
      this.loan.due_date = dueDate.toISOString().split('T')[0];
    },
    
    async submitLoan() {
      this.isSubmitting = true;
      
      try {
        // Simulate API call
        setTimeout(() => {
          this.isSubmitting = false;
          
          // Set created loan data
          this.createdLoan = {
            id: Math.floor(Math.random() * 10000),
            loan_number: `LN${new Date().getTime().toString().slice(-10)}`,
            customer_name: this.customer.full_name,
            asset_title: this.asset.title,
            principal_amount: this.loan.principal_amount,
            due_date: this.loan.due_date
          };
          
          // Show success dialog
          this.successDialog = true;
        }, 1500);
      } catch (error) {
        console.error('Error creating loan:', error);
        this.isSubmitting = false;
      }
    },
    
    goToLoanDetails() {
      // Navigate to loan details page
      this.successDialog = false;
      this.$router.push(`/admin/loans/${this.createdLoan.id}`);
    },
    
    createAnotherLoan() {
      this.successDialog = false;
      this.currentStep = 1;
      this.$refs.step1Form.reset();
      this.$refs.step2Form.reset();
      this.$refs.step3Form.reset();
      
      // Reset to current date
      this.loan.start_date = new Date().toISOString().split('T')[0];
      
      // Clear computed values
      this.loan.interest_amount = '';
      this.loan.storage_charge_amount = '';
      this.loan.total_amount_due = '';
      this.loan.due_date = '';
    },
    
    formatNumber(value) {
      if (!value) return '0.00';
      return parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    },
    
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    }
  }
};
</script>

<style scoped>
.v-stepper {
  box-shadow: none !important;
}

.v-stepper__header {
  box-shadow: none !important;
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.v-stepper__step--complete .v-stepper__step__step {
  background-color: var(--v-success-base) !important;
}

.v-stepper__step--active .v-stepper__step__step {
  background-color: var(--v-primary-base) !important;
}
</style>