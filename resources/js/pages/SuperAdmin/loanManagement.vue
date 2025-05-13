<template>
<SuperAdminLayout>
  <v-container fluid>
    <!-- Header -->
    <v-row>
      <v-col cols="12">
        <div class="d-flex align-center justify-space-between mb-4">
          <h1 class="text-h4">Loan Management</h1>
          <div>
            <InertiaLink :href="route('loan')">
              <v-btn color="success" class="mr-2">
                <v-icon left>mdi-cash-plus</v-icon>
                    Create New Loan
                  </v-btn>
                </InertiaLink>

            <v-btn
              color="primary"
              @click="refreshLoans"
              :loading="isLoading"
            >
              <v-icon left>mdi-refresh</v-icon>
              Refresh
            </v-btn>
          </div>
        </div>
      </v-col>
    </v-row>
    
    <!-- Stats Cards -->
    <v-row>
      <v-col cols="12" sm="6" md="3">
        <v-card class="mx-auto" outlined>
          <v-list-item three-line>
            <v-list-item-content>
              <div class="text-overline mb-1">ACTIVE LOANS</div>
              <v-list-item-title class="text-h4 mb-1">
                {{ loanStats.activeLoans }}
              </v-list-item-title>
              <v-list-item-subtitle>
                <span class="text-caption">Total Value: ${{ formatNumber(loanStats.activeLoanValue) }}</span>
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-avatar size="60" class="mt-n6" color="primary" rounded>
              <v-icon size="32" dark>mdi-cash-multiple</v-icon>
            </v-list-item-avatar>
          </v-list-item>
        </v-card>
      </v-col>
      
      <v-col cols="12" sm="6" md="3">
        <v-card class="mx-auto" outlined>
          <v-list-item three-line>
            <v-list-item-content>
              <div class="text-overline mb-1">OVERDUE LOANS</div>
              <v-list-item-title class="text-h4 mb-1">
                {{ loanStats.overdueLoans }}
              </v-list-item-title>
              <v-list-item-subtitle>
                <span class="text-caption">Total Value: ${{ formatNumber(loanStats.overdueLoanValue) }}</span>
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-avatar size="60" class="mt-n6" color="error" rounded>
              <v-icon size="32" dark>mdi-calendar-alert</v-icon>
            </v-list-item-avatar>
          </v-list-item>
        </v-card>
      </v-col>
      
      <v-col cols="12" sm="6" md="3">
        <v-card class="mx-auto" outlined>
          <v-list-item three-line>
            <v-list-item-content>
              <div class="text-overline mb-1">PENDING LOANS</div>
              <v-list-item-title class="text-h4 mb-1">
                {{ loanStats.pendingLoans }}
              </v-list-item-title>
              <v-list-item-subtitle>
                <span class="text-caption">Total Value: ${{ formatNumber(loanStats.pendingLoanValue) }}</span>
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-avatar size="60" class="mt-n6" color="warning" rounded>
              <v-icon size="32" dark>mdi-clock-outline</v-icon>
            </v-list-item-avatar>
          </v-list-item>
        </v-card>
      </v-col>
      
      <v-col cols="12" sm="6" md="3">
        <v-card class="mx-auto" outlined>
          <v-list-item three-line>
            <v-list-item-content>
              <div class="text-overline mb-1">REVENUE (MTD)</div>
              <v-list-item-title class="text-h4 mb-1">
                ${{ formatNumber(loanStats.monthlyRevenue) }}
              </v-list-item-title>
              <v-list-item-subtitle>
                <v-chip color="success" x-small class="mr-1">+{{ loanStats.revenueGrowth }}%</v-chip>
                <span class="text-caption">from last month</span>
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-avatar size="60" class="mt-n6" color="success" rounded>
              <v-icon size="32" dark>mdi-chart-line</v-icon>
            </v-list-item-avatar>
          </v-list-item>
        </v-card>
      </v-col>
    </v-row>
    
    <!-- Filters & Search -->
    <v-card outlined class="my-4">
      <v-card-text>
        <v-row>
          <v-col cols="12" md="3">
            <v-text-field
              v-model="filters.search"
              label="Search Loans"
              prepend-inner-icon="mdi-magnify"
              outlined
              dense
              hide-details
              clearable
              @input="debouncedSearch"
            ></v-text-field>
          </v-col>
          
          <v-col cols="12" md="2">
            <v-select
              v-model="filters.status"
              :items="statusOptions"
              label="Status"
              outlined
              dense
              hide-details
              clearable
              @change="filterLoans"
            ></v-select>
          </v-col>
          
          <v-col cols="12" md="2">
            <v-select
              v-model="filters.assetType"
              :items="assetTypeOptions"
              label="Asset Type"
              outlined
              dense
              hide-details
              clearable
              @change="filterLoans"
            ></v-select>
          </v-col>
          
          <v-col cols="12" md="2">
            <v-menu
              ref="menuFrom"
              v-model="dateMenus.from"
              :close-on-content-click="false"
              :return-value.sync="filters.dateFrom"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="filters.dateFrom"
                  label="From Date"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  outlined
                  dense
                  hide-details
                  clearable
                  v-bind="attrs"
                  v-on="on"
                  @click:clear="filters.dateFrom = null"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="filters.dateFrom"
                no-title
                scrollable
                @input="dateMenus.from = false; filterLoans();"
              >
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="dateMenus.from = false">Cancel</v-btn>
                <v-btn text color="primary" @click="$refs.menuFrom.save(filters.dateFrom)">OK</v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          
          <v-col cols="12" md="2">
            <v-menu
              ref="menuTo"
              v-model="dateMenus.to"
              :close-on-content-click="false"
              :return-value.sync="filters.dateTo"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="filters.dateTo"
                  label="To Date"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  outlined
                  dense
                  hide-details
                  clearable
                  v-bind="attrs"
                  v-on="on"
                  @click:clear="filters.dateTo = null"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="filters.dateTo"
                no-title
                scrollable
                @input="dateMenus.to = false; filterLoans();"
              >
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="dateMenus.to = false">Cancel</v-btn>
                <v-btn text color="primary" @click="$refs.menuTo.save(filters.dateTo)">OK</v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          
          <v-col cols="12" md="1">
            <v-btn
              color="primary"
              block
              @click="filterLoans"
              :disabled="isLoading"
            >
              Filter
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    
    <!-- Loans Table -->
    <v-card outlined>
      <v-data-table
        :headers="headers"
        :items="loans"
        :loading="isLoading"
        :server-items-length="totalLoans"
        :options.sync="tableOptions"
        :footer-props="{
          'items-per-page-options': [10, 25, 50, 100],
        }"
        class="elevation-0"
      >
        <!-- Table headers with sorting -->
        
        <!-- Loan Number Column -->
        <template v-slot:item.loan_number="{ item }">
          <div class="font-weight-medium">{{ item.loan_number }}</div>
          <div class="caption grey--text">ID: {{ item.id }}</div>
        </template>
        
        <!-- Customer Column -->
        <template v-slot:item.user="{ item }">
          <div class="d-flex align-center">
            <v-avatar size="32" class="mr-2">
              <v-img :src="item.user.avatar || '/img/default-avatar.png'" :alt="item.user.full_name"></v-img>
            </v-avatar>
            <div>
              <div class="font-weight-medium">{{ item.user.full_name }}</div>
              <div class="caption grey--text">{{ item.user.email }}</div>
            </div>
          </div>
        </template>
        
        <!-- Asset Column -->
        <template v-slot:item.asset="{ item }">
          <div class="font-weight-medium">{{ item.asset.title }}</div>
          <div class="caption grey--text">{{ item.asset.asset_type.name }}</div>
        </template>
        
        <!-- Amount Column -->
        <template v-slot:item.principal_amount="{ item }">
          <div>${{ formatNumber(item.principal_amount) }}</div>
          <div class="caption grey--text">Total: ${{ formatNumber(item.total_amount_due) }}</div>
        </template>
        
        <!-- Outstanding Balance Column -->
        <template v-slot:item.outstanding_balance="{ item }">
          <div>${{ formatNumber(item.outstanding_balance) }}</div>
          <div class="caption grey--text">
            Paid: ${{ formatNumber(item.amount_paid) }}
          </div>
        </template>
        
        <!-- Dates Column -->
        <template v-slot:item.start_date="{ item }">
          <div>{{ formatDate(item.start_date) }}</div>
          <div class="caption grey--text">Due: {{ formatDate(item.due_date) }}</div>
        </template>
        
        <!-- Status Column -->
        <template v-slot:item.status="{ item }">
          <v-chip :color="getLoanStatusColor(item.status)" small>
            {{ item.status }}
          </v-chip>
          <div class="caption mt-1" v-if="item.status === 'Active' && isOverdue(item)">
            <v-chip color="error" x-small>{{ getDaysOverdue(item) }} days overdue</v-chip>
          </div>
        </template>
        
        <!-- Actions Column -->
        <template v-slot:item.actions="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                v-bind="attrs"
                v-on="on"
                @click="viewLoanDetails(item)"
              >
                <v-icon small>mdi-eye</v-icon>
              </v-btn>
            </template>
            <span>View Details</span>
          </v-tooltip>
          
          <v-tooltip bottom v-if="item.status === 'Pending'">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                color="success"
                v-bind="attrs"
                v-on="on"
                @click="approveLoan(item)"
              >
                <v-icon small>mdi-check</v-icon>
              </v-btn>
            </template>
            <span>Approve Loan</span>
          </v-tooltip>
          
          <v-tooltip bottom v-if="item.status === 'Pending'">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                color="error"
                v-bind="attrs"
                v-on="on"
                @click="rejectLoan(item)"
              >
                <v-icon small>mdi-close</v-icon>
              </v-btn>
            </template>
            <span>Reject Loan</span>
          </v-tooltip>
          
          <v-tooltip bottom v-if="['Active', 'Overdue'].includes(item.status)">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                color="primary"
                v-bind="attrs"
                v-on="on"
                @click="recordPayment(item)"
              >
                <v-icon small>mdi-cash-check</v-icon>
              </v-btn>
            </template>
            <span>Record Payment</span>
          </v-tooltip>
          
          <v-tooltip bottom v-if="item.status === 'Overdue'">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                color="warning"
                v-bind="attrs"
                v-on="on"
                @click="sendToAuction(item)"
              >
                <v-icon small>mdi-gavel</v-icon>
              </v-btn>
            </template>
            <span>Send to Auction</span>
          </v-tooltip>
        </template>
        
        <!-- No data template -->
        <template v-slot:no-data>
          <div class="text-center py-3">
            <v-icon x-large color="grey lighten-1">mdi-database-off</v-icon>
            <div class="mt-2 grey--text">No loans found</div>
            <v-btn color="primary" text class="mt-4" @click="resetFilters">
              Reset Filters
            </v-btn>
          </div>
        </template>
      </v-data-table>
    </v-card>
    
    <!-- Loan Detail Dialog -->
    <v-dialog v-model="detailDialog.show" max-width="1000px" scrollable>
      <v-card v-if="detailDialog.loan">
        <v-card-title class="headline">
          <v-icon left>mdi-cash-multiple</v-icon>
          Loan Details: {{ detailDialog.loan.loan_number }}
          <v-spacer></v-spacer>
          <v-btn icon @click="detailDialog.show = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        
        <v-divider></v-divider>
        
        <v-card-text>
          <v-row>
            <!-- Loan Summary -->
            <v-col cols="12" md="6">
              <v-list dense>
                <v-subheader>Loan Information</v-subheader>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Status</v-list-item-title>
                    <v-list-item-subtitle>
                      <v-chip :color="getLoanStatusColor(detailDialog.loan.status)" small>
                        {{ detailDialog.loan.status }}
                      </v-chip>
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Principal Amount</v-list-item-title>
                    <v-list-item-subtitle class="font-weight-medium">${{ formatNumber(detailDialog.loan.principal_amount) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Interest Rate</v-list-item-title>
                    <v-list-item-subtitle>{{ detailDialog.loan.interest_rate }}%</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Storage Charge</v-list-item-title>
                    <v-list-item-subtitle>${{ formatNumber(detailDialog.loan.storage_charge_amount) }} ({{ detailDialog.loan.storage_charge_percentage }}%)</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Total Amount Due</v-list-item-title>
                    <v-list-item-subtitle class="font-weight-medium">${{ formatNumber(detailDialog.loan.total_amount_due) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Amount Paid</v-list-item-title>
                    <v-list-item-subtitle>${{ formatNumber(detailDialog.loan.amount_paid) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Outstanding Balance</v-list-item-title>
                    <v-list-item-subtitle class="font-weight-medium">${{ formatNumber(detailDialog.loan.outstanding_balance) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-divider></v-divider>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Start Date</v-list-item-title>
                    <v-list-item-subtitle>{{ formatDate(detailDialog.loan.start_date) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Due Date</v-list-item-title>
                    <v-list-item-subtitle class="font-weight-medium">{{ formatDate(detailDialog.loan.due_date) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Grace Period End Date</v-list-item-title>
                    <v-list-item-subtitle>{{ formatDate(detailDialog.loan.grace_period_end_date) }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                
                <v-list-item two-line>
                  <v-list-item-content>
                    <v-list-item-title>Last Payment Date</v-list-item-title>
                    <v-list-item-subtitle>{{ detailDialog.loan.last_payment_date ? formatDate(detailDialog.loan.last_payment_date) : 'No payments yet' }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
            </v-col>
            
            <!-- Customer & Asset Info -->
            <v-col cols="12" md="6">
              <v-card outlined>
                <v-card-title class="subtitle-1">
                  <v-icon left>mdi-account</v-icon>
                  Customer Information
                </v-card-title>
                
                <v-card-text>
                  <div class="d-flex align-center mb-4">
                    <v-avatar size="40" class="mr-3">
                      <v-img :src="detailDialog.loan.user.avatar || '/img/default-avatar.png'" :alt="detailDialog.loan.user.full_name"></v-img>
                    </v-avatar>
                    <div>
                      <div class="text-h6">{{ detailDialog.loan.user.full_name }}</div>
                      <div class="caption">Customer ID: {{ detailDialog.loan.user.id }}</div>
                    </div>
                    <v-spacer></v-spacer>
                    <v-btn
                      small
                      outlined
                      color="primary"
                      :to="`/admin/users/${detailDialog.loan.user.id}`"
                    >
                      <v-icon left small>mdi-account-details</v-icon>
                      View Profile
                    </v-btn>
                  </div>
                  
                  <v-row dense>
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-icon class="mr-2">
                          <v-icon small>mdi-email</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-subtitle>{{ detailDialog.loan.user.email }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-icon class="mr-2">
                          <v-icon small>mdi-phone</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-subtitle>{{ detailDialog.loan.user.phone_number }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-icon class="mr-2">
                          <v-icon small>mdi-card-account-details</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-subtitle>ID: {{ detailDialog.loan.user.national_id }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-icon class="mr-2">
                          <v-icon small>mdi-map-marker</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-subtitle>{{ detailDialog.loan.user.location }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12">
                      <v-list-item dense>
                        <v-list-item-icon class="mr-2">
                          <v-icon small>mdi-star</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-subtitle>
                            Credit Score: {{ detailDialog.loan.credit_score || 'N/A' }}
                            <v-chip x-small :color="getCreditScoreColor(detailDialog.loan.credit_score)" v-if="detailDialog.loan.credit_score">
                              {{ getCreditScoreLabel(detailDialog.loan.credit_score) }}
                            </v-chip>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
              
              <v-card outlined class="mt-4">
                <v-card-title class="subtitle-1">
                  <v-icon left>mdi-package-variant-closed</v-icon>
                  Asset Information
                </v-card-title>
                
                <v-card-text>
                  <div class="d-flex align-start mb-3">
                    <div class="flex-grow-1">
                      <div class="text-h6">{{ detailDialog.loan.asset.title }}</div>
                      <div class="subtitle-2">{{ detailDialog.loan.asset.asset_type.name }}</div>
                      <div class="caption">Asset ID: {{ detailDialog.loan.asset.id }}</div>
                    </div>
                    
                    <v-btn
                      small
                      outlined
                      color="primary"
                      :to="`/admin/assets/${detailDialog.loan.asset.id}`"
                    >
                      <v-icon left small>mdi-eye</v-icon>
                      View Asset
                    </v-btn>
                  </div>
                  
                  <v-divider class="my-3"></v-divider>
                  
                  <v-row dense>
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-content>
                          <v-list-item-title class="caption">Description</v-list-item-title>
                          <v-list-item-subtitle>{{ detailDialog.loan.asset.description }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-content>
                          <v-list-item-title class="caption">Condition</v-list-item-title>
                          <v-list-item-subtitle>{{ detailDialog.loan.asset.condition }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-content>
                          <v-list-item-title class="caption">Market Value</v-list-item-title>
                          <v-list-item-subtitle>${{ formatNumber(detailDialog.loan.asset.market_value) }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-content>
                          <v-list-item-title class="caption">Final Evaluation Value</v-list-item-title>
                          <v-list-item-subtitle>${{ formatNumber(detailDialog.loan.asset.final_evaluation_value) }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-content>
                          <v-list-item-title class="caption">Loan Value</v-list-item-title>
                          <v-list-item-subtitle>${{ formatNumber(detailDialog.loan.asset.final_loan_value) }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                    
                    <v-col cols="12" md="6">
                      <v-list-item dense>
                        <v-list-item-content>
                          <v-list-item-title class="caption">Storage Location</v-list-item-title>
                          <v-list-item-subtitle>{{ detailDialog.loan.asset.storage_location || 'Not specified' }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-col>
                  </v-row>
                  <!-- Asset Images -->
<div class="mt-3" v-if="detailDialog.loan.asset.images && detailDialog.loan.asset.images.length">
  <v-subheader class="pl-0">Asset Images</v-subheader>
  <v-row>
    <v-col cols="6" md="3" v-for="(image, index) in detailDialog.loan.asset.images" :key="index">
      <v-card outlined>
        <v-img
          :src="image.image_path"
          aspect-ratio="1"
          class="grey lighten-2"
          contain
          @click="openImagePreview(image.image_path)"
        ></v-img>
      </v-card>
    </v-col>
  </v-row>
</div>
</v-card-text>
</v-card>

<!-- Loan History -->
<v-card outlined class="mt-4">
  <v-card-title class="subtitle-1">
    <v-icon left>mdi-history</v-icon>
    Loan Activity History
  </v-card-title>
  
  <v-card-text class="pa-0">
    <v-timeline dense>
      <v-timeline-item
        v-for="(activity, index) in detailDialog.loan.history"
        :key="index"
        :color="getActivityColor(activity.type)"
        small
      >
        <div class="font-weight-medium">{{ activity.title }}</div>
        <div class="caption">{{ activity.description }}</div>
        <div class="caption grey--text">{{ formatDateTime(activity.created_at) }}</div>
      </v-timeline-item>
    </v-timeline>
  </v-card-text>
</v-card>
</v-col>
</v-row>

<!-- Payment History -->
<v-row>
  <v-col cols="12">
    <v-card outlined>
      <v-card-title class="subtitle-1">
        <v-icon left>mdi-cash-multiple</v-icon>
        Payment History
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          small
          @click="recordPayment(detailDialog.loan)"
          v-if="['Active', 'Overdue'].includes(detailDialog.loan.status)"
        >
          <v-icon left small>mdi-cash-plus</v-icon>
          Record Payment
        </v-btn>
      </v-card-title>
      
      <v-data-table
        :headers="paymentHeaders"
        :items="detailDialog.loan.payments || []"
        :items-per-page="5"
        :footer-props="{
          'items-per-page-options': [5, 10, 20],
        }"
        class="elevation-0"
      >
        <template v-slot:item.amount="{ item }">
          ${{ formatNumber(item.amount) }}
        </template>
        
        <template v-slot:item.created_at="{ item }">
          {{ formatDateTime(item.created_at) }}
        </template>
        
        <template v-slot:item.status="{ item }">
          <v-chip :color="getPaymentStatusColor(item.status)" x-small>
            {{ item.status }}
          </v-chip>
        </template>
        
        <template v-slot:item.received_by="{ item }">
          {{ item.receiver ? item.receiver.full_name : 'System' }}
        </template>
        
        <template v-slot:no-data>
          <div class="text-center py-3">
            <p class="mb-0 grey--text">No payments recorded for this loan</p>
          </div>
        </template>
      </v-data-table>
    </v-card>
  </v-col>
</v-row>
</v-card-text>

<v-divider></v-divider>

<v-card-actions>
  <v-btn color="primary" text @click="printLoanDetails">
    <v-icon left>mdi-printer</v-icon>
    Print Details
  </v-btn>
  
  <v-btn color="primary" text @click="exportLoanDetails">
    <v-icon left>mdi-file-export</v-icon>
    Export
  </v-btn>
  
  <v-spacer></v-spacer>
  
  <v-btn color="grey darken-1" text @click="detailDialog.show = false">
    Close
  </v-btn>
</v-card-actions>
</v-card>
</v-dialog>

<!-- Payment Recording Dialog -->
<v-dialog v-model="paymentDialog.show" max-width="600px">
  <v-card>
    <v-card-title class="headline">
      <v-icon left>mdi-cash-plus</v-icon>
      Record Payment
    </v-card-title>
    
    <v-card-text>
      <v-form ref="paymentForm" v-model="paymentDialog.valid">
        <v-row>
          <v-col cols="12" class="pb-0">
            <p class="mb-1">Loan: <strong>{{ paymentDialog.loan ? paymentDialog.loan.loan_number : '' }}</strong></p>
            <p class="mb-1">Customer: <strong>{{ paymentDialog.loan ? paymentDialog.loan.user.full_name : '' }}</strong></p>
            <p class="mb-1">Outstanding Balance: <strong>${{ paymentDialog.loan ? formatNumber(paymentDialog.loan.outstanding_balance) : '0.00' }}</strong></p>
          </v-col>
          
          <v-col cols="12">
            <v-text-field
              v-model="paymentDialog.amount"
              label="Payment Amount"
              type="number"
              min="0"
              :max="paymentDialog.loan ? paymentDialog.loan.outstanding_balance : 0"
              step="0.01"
              prefix="$"
              :rules="[
                v => !!v || 'Amount is required',
                v => v > 0 || 'Amount must be greater than 0',
                v => v <= (paymentDialog.loan ? paymentDialog.loan.outstanding_balance : 0) || 'Amount cannot exceed outstanding balance'
              ]"
              outlined
              required
            ></v-text-field>
          </v-col>
          
          <v-col cols="12">
            <v-select
              v-model="paymentDialog.paymentMethod"
              :items="paymentMethodOptions"
              label="Payment Method"
              :rules="[v => !!v || 'Payment method is required']"
              outlined
              required
            ></v-select>
          </v-col>
          
          <v-col cols="12">
            <v-text-field
              v-model="paymentDialog.transactionReference"
              label="Transaction Reference"
              :rules="[v => !!v || 'Transaction reference is required']"
              outlined
              required
            ></v-text-field>
          </v-col>
          
          <v-col cols="12">
            <v-textarea
              v-model="paymentDialog.notes"
              label="Notes"
              outlined
              rows="3"
            ></v-textarea>
          </v-col>
          
          <v-col cols="12">
            <v-checkbox
              v-model="paymentDialog.sendReceipt"
              label="Send receipt to customer"
            ></v-checkbox>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    
    <v-divider></v-divider>
    
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="grey darken-1" text @click="paymentDialog.show = false" :disabled="paymentDialog.loading">
        Cancel
      </v-btn>
      <v-btn color="primary" :loading="paymentDialog.loading" :disabled="!paymentDialog.valid || paymentDialog.loading" @click="submitPayment">
        Record Payment
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>

<!-- Loan Approval Dialog -->
<v-dialog v-model="approvalDialog.show" max-width="600px">
  <v-card>
    <v-card-title class="headline">
      <v-icon left>mdi-check-circle</v-icon>
      Approve Loan
    </v-card-title>
    
    <v-card-text>
      <v-form ref="approvalForm" v-model="approvalDialog.valid">
        <v-row>
          <v-col cols="12" class="pb-0">
            <p class="mb-1">Loan: <strong>{{ approvalDialog.loan ? approvalDialog.loan.loan_number : '' }}</strong></p>
            <p class="mb-1">Customer: <strong>{{ approvalDialog.loan ? approvalDialog.loan.user.full_name : '' }}</strong></p>
            <p class="mb-1">Asset: <strong>{{ approvalDialog.loan ? approvalDialog.loan.asset.title : '' }}</strong></p>
            <p class="mb-1">Loan Amount: <strong>${{ approvalDialog.loan ? formatNumber(approvalDialog.loan.principal_amount) : '0.00' }}</strong></p>
          </v-col>
          
          <v-col cols="12">
            <v-text-field
              v-model="approvalDialog.creditScore"
              label="Credit Score"
              type="number"
              min="0"
              max="1000"
              :rules="[v => !!v || 'Credit score is required']"
              outlined
              hint="Customer's FCB credit score"
              persistent-hint
              required
            ></v-text-field>
          </v-col>
          
          <v-col cols="12">
            <v-textarea
              v-model="approvalDialog.notes"
              label="Approval Notes"
              outlined
              rows="3"
            ></v-textarea>
          </v-col>
          
          <v-col cols="12">
            <v-checkbox
              v-model="approvalDialog.notifyCustomer"
              label="Notify customer about loan approval"
            ></v-checkbox>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    
    <v-divider></v-divider>
    
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="grey darken-1" text @click="approvalDialog.show = false" :disabled="approvalDialog.loading">
        Cancel
      </v-btn>
      <v-btn color="success" :loading="approvalDialog.loading" :disabled="!approvalDialog.valid || approvalDialog.loading" @click="submitApproval">
        Approve Loan
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>

<!-- Loan Rejection Dialog -->
<v-dialog v-model="rejectionDialog.show" max-width="600px">
  <v-card>
    <v-card-title class="headline">
      <v-icon left>mdi-close-circle</v-icon>
      Reject Loan
    </v-card-title>
    
    <v-card-text>
      <v-form ref="rejectionForm" v-model="rejectionDialog.valid">
        <v-row>
          <v-col cols="12" class="pb-0">
            <p class="mb-1">Loan: <strong>{{ rejectionDialog.loan ? rejectionDialog.loan.loan_number : '' }}</strong></p>
            <p class="mb-1">Customer: <strong>{{ rejectionDialog.loan ? rejectionDialog.loan.user.full_name : '' }}</strong></p>
            <p class="mb-1">Asset: <strong>{{ rejectionDialog.loan ? rejectionDialog.loan.asset.title : '' }}</strong></p>
            <p class="mb-1">Loan Amount: <strong>${{ rejectionDialog.loan ? formatNumber(rejectionDialog.loan.principal_amount) : '0.00' }}</strong></p>
          </v-col>
          
          <v-col cols="12">
            <v-select
              v-model="rejectionDialog.reason"
              :items="rejectionReasonOptions"
              label="Rejection Reason"
              :rules="[v => !!v || 'Rejection reason is required']"
              outlined
              required
            ></v-select>
          </v-col>
          
          <v-col cols="12">
            <v-textarea
              v-model="rejectionDialog.notes"
              label="Additional Notes"
              :rules="[v => !!v || 'Additional notes are required']"
              outlined
              rows="3"
              required
            ></v-textarea>
          </v-col>
          
          <v-col cols="12">
            <v-checkbox
              v-model="rejectionDialog.notifyCustomer"
              label="Notify customer about loan rejection"
            ></v-checkbox>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
    
    <v-divider></v-divider>
    
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="grey darken-1" text @click="rejectionDialog.show = false" :disabled="rejectionDialog.loading">
        Cancel
      </v-btn>
      <v-btn color="error" :loading="rejectionDialog.loading" :disabled="!rejectionDialog.valid || rejectionDialog.loading" @click="submitRejection">
        Reject Loan
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>

<!-- Auction Confirmation Dialog -->
<v-dialog v-model="auctionDialog.show" max-width="500px">
  <v-card>
    <v-card-title class="headline">
      <v-icon left>mdi-gavel</v-icon>
      Send to Auction
    </v-card-title>
    
    <v-card-text>
      <p>Are you sure you want to send the following defaulted loan to auction?</p>
      
      <v-list dense class="mt-3">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Loan Number</v-list-item-title>
            <v-list-item-subtitle>{{ auctionDialog.loan ? auctionDialog.loan.loan_number : '' }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Customer</v-list-item-title>
            <v-list-item-subtitle>{{ auctionDialog.loan ? auctionDialog.loan.user.full_name : '' }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Asset</v-list-item-title>
            <v-list-item-subtitle>{{ auctionDialog.loan ? auctionDialog.loan.asset.title : '' }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Outstanding Amount</v-list-item-title>
            <v-list-item-subtitle>${{ auctionDialog.loan ? formatNumber(auctionDialog.loan.outstanding_balance) : '0.00' }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      
      <v-alert type="warning" dense class="mt-3">
        This action will mark the loan as defaulted and add the asset to the next available auction. The customer will be notified about this action.
      </v-alert>
    </v-card-text>
    
    <v-divider></v-divider>
    
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="grey darken-1" text @click="auctionDialog.show = false" :disabled="auctionDialog.loading">
        Cancel
      </v-btn>
      <v-btn color="warning" :loading="auctionDialog.loading" :disabled="auctionDialog.loading" @click="confirmSendToAuction">
        Send to Auction
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>

<!-- Image Preview Dialog -->
<v-dialog v-model="imagePreviewDialog.show" max-width="800px">
  <v-card>
    <v-card-title class="headline d-flex justify-space-between align-center">
      Asset Image
      <v-btn icon @click="imagePreviewDialog.show = false">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-card-title>
    
    <v-card-text class="text-center">
      <v-img
        :src="imagePreviewDialog.url"
        max-height="600"
        contain
        class="grey lighten-3"
      ></v-img>
    </v-card-text>
  </v-card>
</v-dialog>

<!-- Snackbar for notifications -->
<v-snackbar
  v-model="snackbar.show"
  :color="snackbar.color"
  :timeout="snackbar.timeout"
>
  {{ snackbar.text }}
  <template v-slot:action="{ attrs }">
    <v-btn
      text
      v-bind="attrs"
      @click="snackbar.show = false"
    >
      Close
    </v-btn>
  </template>
</v-snackbar>
</v-container>
</SuperAdminLayout>
</template>

<script>
import axios from 'axios';
import debounce from 'lodash/debounce';
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue';

export default {
  name: 'LoanManagement',
  layout: SuperAdminLayout,
  data() {
    return {
      // Table data and loading state
      loans: [],
      totalLoans: 0,
      isLoading: false,
      
      // Table options (for pagination, sorting)
      tableOptions: {
        page: 1,
        itemsPerPage: 10,
        sortBy: ['created_at'],
        sortDesc: [true]
      },
      
      // Statistics data
      loanStats: {
        activeLoans: 0,
        activeLoanValue: 0,
        overdueLoans: 0,
        overdueLoanValue: 0,
        pendingLoans: 0,
        pendingLoanValue: 0,
        monthlyRevenue: 0,
        revenueGrowth: 0
      },
      
      // Search filters
      filters: {
        search: '',
        status: null,
        assetType: null,
        dateFrom: null,
        dateTo: null
      },
      
      // Date picker menus
      dateMenus: {
        from: false,
        to: false
      },
      
      // Table headers
      headers: [
        { text: 'Loan Number', value: 'loan_number', sortable: true },
        { text: 'Customer', value: 'user', sortable: false },
        { text: 'Asset', value: 'asset', sortable: false },
        { text: 'Amount', value: 'principal_amount', sortable: true },
        { text: 'Balance', value: 'outstanding_balance', sortable: true },
        { text: 'Dates', value: 'start_date', sortable: true },
        { text: 'Status', value: 'status', sortable: true },
        { text: 'Actions', value: 'actions', sortable: false, align: 'center' }
      ],
      
      // Payment history headers
      paymentHeaders: [
        { text: 'Transaction ID', value: 'transaction_reference' },
        { text: 'Amount', value: 'amount' },
        { text: 'Date', value: 'created_at' },
        { text: 'Method', value: 'payment_method' },
        { text: 'Status', value: 'status' },
        { text: 'Received By', value: 'received_by' }
      ],
      
      // Filter options
      statusOptions: [
        'Pending', 'Approved', 'Active', 'Overdue', 'Paid', 'Defaulted', 'In Auction'
      ],
      assetTypeOptions: [
        'Vehicle', 'Jewelry', 'Electronics', 'Collectibles', 'Furniture'
      ],
      paymentMethodOptions: [
        'Cash', 'Bank Transfer', 'Mobile Money', 'Credit Card', 'Debit Card'
      ],
      rejectionReasonOptions: [
        'Insufficient Asset Value',
        'Poor Credit History',
        'Missing Documentation',
        'Asset Verification Failed',
        'Suspected Fraudulent Activity',
        'Exceeds Customer Debt Limit',
        'Other'
      ],
      
      // Detail dialog
      detailDialog: {
        show: false,
        loan: null
      },
      
      // Payment dialog
      paymentDialog: {
        show: false,
        loan: null,
        amount: null,
        paymentMethod: null,
        transactionReference: '',
        notes: '',
        sendReceipt: true,
        valid: false,
        loading: false
      },
      
      // Approval dialog
      approvalDialog: {
        show: false,
        loan: null,
        creditScore: null,
        notes: '',
        notifyCustomer: true,
        valid: false,
        loading: false
      },
      
      // Rejection dialog
      rejectionDialog: {
        show: false,
        loan: null,
        reason: null,
        notes: '',
        notifyCustomer: true,
        valid: false,
        loading: false
      },
      
      // Auction dialog
      auctionDialog: {
        show: false,
        loan: null,
        loading: false
      },
      
      // Image preview dialog
      imagePreviewDialog: {
        show: false,
        url: ''
      },
      
      // Snackbar
      snackbar: {
        show: false,
        text: '',
        color: 'success',
        timeout: 3000
      }
    };
  },
  
  created() {
    // Initialize debounced search
    this.debouncedSearch = debounce(() => {
      this.filterLoans();
    }, 500);
    
    // Load initial data
    this.fetchLoanStats();
    this.fetchLoans();
  },
  
  watch: {
    tableOptions: {
      handler() {
        this.fetchLoans();
      },
      deep: true
    }
  },
  
  methods: {
    // Data fetching methods
    async fetchLoanStats() {
      try {
        const response = await axios.get('/api/admin/loans/stats');
        this.loanStats = response.data;
      } catch (error) {
        console.error('Error fetching loan statistics:', error);
        this.showSnackbar('Error loading loan statistics', 'error');
      }
    },
    
    async fetchLoans() {
      this.isLoading = true;
      
      try {
        // Prepare query parameters
        const params = {
          page: this.tableOptions.page,
          per_page: this.tableOptions.itemsPerPage,
          search: this.filters.search || undefined,
          status: this.filters.status || undefined,
          asset_type: this.filters.assetType || undefined,
          date_from: this.filters.dateFrom || undefined,
          date_to: this.filters.dateTo || undefined
        };
        
        // Add sorting
        if (this.tableOptions.sortBy.length) {
          params.sort_by = this.tableOptions.sortBy[0];
          params.sort_desc = this.tableOptions.sortDesc[0];
        }
        
        // Make API request
        const response = await axios.get('/api/admin/loans', { params });
        
        this.loans = response.data.data;
        this.totalLoans = response.data.meta.total;
      } catch (error) {
        console.error('Error fetching loans:', error);
        this.showSnackbar('Error loading loans data', 'error');
      } finally {
        this.isLoading = false;
      }
    },
    
    // Action methods
    refreshLoans() {
      this.fetchLoanStats();
      this.fetchLoans();
    },
    
    filterLoans() {
      // Reset pagination to first page when filtering
      this.tableOptions.page = 1;
      this.fetchLoans();
    },
    
    resetFilters() {
      this.filters = {
        search: '',
        status: null,
        assetType: null,
        dateFrom: null,
        dateTo: null
      };
      this.filterLoans();
    },
    
    async viewLoanDetails(loan) {
      this.isLoading = true;
      
      try {
        // Fetch detailed loan information
        const response = await axios.get(`/api/admin/loans/${loan.id}`);
        this.detailDialog.loan = response.data;
        this.detailDialog.show = true;
      } catch (error) {
        console.error('Error fetching loan details:', error);
        this.showSnackbar('Error loading loan details', 'error');
      } finally {
        this.isLoading = false;
      }
    },
    
    approveLoan(loan) {
      this.approvalDialog.loan = loan;
      this.approvalDialog.creditScore = loan.credit_score || 650; // Default value
      this.approvalDialog.notes = '';
      this.approvalDialog.notifyCustomer = true;
      this.approvalDialog.show = true;
    },
    
    async submitApproval() {
      if (!this.$refs.approvalForm.validate()) return;
      
      this.approvalDialog.loading = true;
      
      try {
        await axios.post(`/api/admin/loans/${this.approvalDialog.loan.id}/approve`, {
          credit_score: this.approvalDialog.creditScore,
          notes: this.approvalDialog.notes,
          notify_customer: this.approvalDialog.notifyCustomer
        });
        
        // Show success message
        this.showSnackbar('Loan approved successfully', 'success');
        
        // Close dialog and refresh data
        this.approvalDialog.show = false;
        this.refreshLoans();
        
        // If loan details dialog is open, update it
        if (this.detailDialog.show && this.detailDialog.loan.id === this.approvalDialog.loan.id) {
          this.viewLoanDetails(this.approvalDialog.loan);
        }
      } catch (error) {
        console.error('Error approving loan:', error);
        this.showSnackbar('Error approving loan', 'error');
      } finally {
        this.approvalDialog.loading = false;
      }
    },
    
    rejectLoan(loan) {
      this.rejectionDialog.loan = loan;
      this.rejectionDialog.reason = null;
      this.rejectionDialog.notes = '';
      this.rejectionDialog.notifyCustomer = true;
      this.rejectionDialog.show = true;
    },
    
    async submitRejection() {
      if (!this.$refs.rejectionForm.validate()) return;
      
      this.rejectionDialog.loading = true;
      
      try {
        await axios.post(`/api/admin/loans/${this.rejectionDialog.loan.id}/reject`, {
          reason: this.rejectionDialog.reason,
          notes: this.rejectionDialog.notes,
          notify_customer: this.rejectionDialog.notifyCustomer
        });
        
        // Show success message
        this.showSnackbar('Loan rejected successfully', 'success');
        
        // Close dialog and refresh data
        this.rejectionDialog.show = false;
        this.refreshLoans();
      } catch (error) {
        console.error('Error rejecting loan:', error);
        this.showSnackbar('Error rejecting loan', 'error');
      } finally {
        this.rejectionDialog.loading = false;
      }
    },
    
    recordPayment(loan) {
      this.paymentDialog.loan = loan;
      this.paymentDialog.amount = null;
      this.paymentDialog.paymentMethod = null;
      this.paymentDialog.transactionReference = '';
      this.paymentDialog.notes = '';
      this.paymentDialog.sendReceipt = true;
      this.paymentDialog.show = true;
    },
    
    async submitPayment() {
      if (!this.$refs.paymentForm.validate()) return;
      
      this.paymentDialog.loading = true;
      
      try {
        await axios.post(`/api/admin/loans/${this.paymentDialog.loan.id}/payments`, {
          amount: this.paymentDialog.amount,
          payment_method: this.paymentDialog.paymentMethod,
          transaction_reference: this.paymentDialog.transactionReference,
          notes: this.paymentDialog.notes,
          send_receipt: this.paymentDialog.sendReceipt
        });
        
        // Show success message
        this.showSnackbar('Payment recorded successfully', 'success');
        
        // Close dialog and refresh data
        this.paymentDialog.show = false;
        this.refreshLoans();
        
        // If loan details dialog is open, update it
        if (this.detailDialog.show && this.detailDialog.loan.id === this.paymentDialog.loan.id) {
          this.viewLoanDetails(this.paymentDialog.loan);
        }
      } catch (error) {
        console.error('Error recording payment:', error);
        this.showSnackbar('Error recording payment', 'error');
      } finally {
        this.paymentDialog.loading = false;
      }
    },
    
    sendToAuction(loan) {
      this.auctionDialog.loan = loan;
      this.auctionDialog.show = true;
    },
    
    async confirmSendToAuction() {
      this.auctionDialog.loading = true;
      
      try {
        await axios.post(`/api/admin/loans/${this.auctionDialog.loan.id}/send-to-auction`);
        
        // Show success message
        this.showSnackbar('Asset sent to auction successfully', 'success');
        
        // Close dialog and refresh data
        this.auctionDialog.show = false;
        this.refreshLoans();
      } catch (error) {
        console.error('Error sending to auction:', error);
        this.showSnackbar('Error sending to auction', 'error');
      } finally {
        this.auctionDialog.loading = false;
      }
    },
    
    openImagePreview(url) {
      this.imagePreviewDialog.url = url;
      this.imagePreviewDialog.show = true;
    },
    
    printLoanDetails() {
      // Implement print functionality
      window.print();
    },
    
    exportLoanDetails() {
      // Implement export functionality (e.g., to PDF or Excel)
      // This would typically use a library like jsPDF or xlsx
      alert('Export functionality would be implemented here.');
    },
    
    // Utility methods
    formatNumber(value) {
      if (!value) return '0.00';
      return parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    },
    
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    },
    
    formatDateTime(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    
    getLoanStatusColor(status) {
      const colors = {
        'Pending': 'warning',
        'Approved': 'info',
        'Active': 'primary',
        'Overdue': 'error',
        'Paid': 'success',
        'Defaulted': 'grey darken-1',
        'In Auction': 'deep-orange'
      };
      return colors[status] || 'grey';
    },
    
    getPaymentStatusColor(status) {
      const colors = {
        'Pending': 'warning',
        'Completed': 'success',
        'Failed': 'error'
      };
      return colors[status] || 'grey';
    },
    
    getActivityColor(type) {
      const colors = {
        'loan_created': 'primary',
        'loan_approved': 'success',
        'loan_rejected': 'error',
        'payment_received': 'green',
        'loan_paid': 'teal',
        'loan_defaulted': 'red',
        'sent_to_auction': 'orange'
      };
      return colors[type] || 'grey';
    },
    
    getCreditScoreColor(score) {
      if (!score) return 'grey';
      if (score >= 750) return 'success';
      if (score >= 650) return 'info';
      if (score >= 550) return 'warning';
      return 'error';
    },
    
    getCreditScoreLabel(score) {
      if (!score) return 'N/A';
      if (score >= 750) return 'Excellent';
      if (score >= 650) return 'Good';
      if (score >= 550) return 'Fair';
      return 'Poor';
    },
    
    isOverdue(loan) {
      if (loan.status !== 'Active') return false;
      const dueDate = new Date(loan.due_date);
      const today = new Date();
      return today > dueDate;
    },
    
    getDaysOverdue(loan) {
      if (!this.isOverdue(loan)) return 0;
      const dueDate = new Date(loan.due_date);
      const today = new Date();
      const diffTime = Math.abs(today - dueDate);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },
    
    showSnackbar(text, color = 'success', timeout = 3000) {
      this.snackbar.text = text;
      this.snackbar.color = color;
      this.snackbar.timeout = timeout;
      this.snackbar.show = true;
    }
  }
};
</script>

<style scoped>
/* For print styles */
@media print {
  .v-application--wrap {
    padding: 0 !important;
  }
  
  .v-main {
    padding: 0 !important;
  }
  
  header, footer, button, .v-card__actions, .v-data-table__wrapper > table > thead > tr > th:last-child {
    display: none !important;
  }
}

/* Reduce spacing in timeline */
.v-timeline-item__body {
  padding-bottom: 8px !important;
}
</style>
                  
