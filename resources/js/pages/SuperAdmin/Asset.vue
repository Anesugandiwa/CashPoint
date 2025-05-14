<template>
  <SuperAdminLayout>
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
              <div>
                <h2 class="text-h5">Asset Management</h2>
                <p class="text-subtitle-1 grey--text">Manage all submitted assets in the system</p>
              </div>
              <div>
                <v-btn color="primary" class="mr-2">
                  <v-icon left>mdi-file-export</v-icon>
                  Export
                </v-btn>
                <v-btn color="success">
                  <v-icon left>mdi-refresh</v-icon>
                  Refresh
                </v-btn>
              </div>
            </v-card-title>

            <v-card-text>
              <!-- Statistics Cards -->
              <v-row>
                <v-col cols="12" md="3">
                  <v-card outlined>
                    <v-card-text>
                      <div class="d-flex justify-space-between align-center">
                        <div>
                          <div class="text-overline mb-1">Total Assets</div>
                          <div class="text-h4">{{ assets.length }}</div>
                        </div>
                        <v-icon x-large color="primary">mdi-package-variant-closed</v-icon>
                      </div>
                    </v-card-text>
                  </v-card>
                </v-col>
                
                <v-col cols="12" md="3">
                  <v-card outlined>
                    <v-card-text>
                      <div class="d-flex justify-space-between align-center">
                        <div>
                          <div class="text-overline mb-1">Pending Evaluation</div>
                          <div class="text-h4">{{ pendingEvaluationCount }}</div>
                        </div>
                        <v-icon x-large color="warning">mdi-timer-sand</v-icon>
                      </div>
                    </v-card-text>
                  </v-card>
                </v-col>
                
                <v-col cols="12" md="3">
                  <v-card outlined>
                    <v-card-text>
                      <div class="d-flex justify-space-between align-center">
                        <div>
                          <div class="text-overline mb-1">Active Assets</div>
                          <div class="text-h4">{{ activeAssetsCount }}</div>
                        </div>
                        <v-icon x-large color="success">mdi-check-circle</v-icon>
                      </div>
                    </v-card-text>
                  </v-card>
                </v-col>
                
                <v-col cols="12" md="3">
                  <v-card outlined>
                    <v-card-text>
                      <div class="d-flex justify-space-between align-center">
                        <div>
                          <div class="text-overline mb-1">Total Value</div>
                          <div class="text-h4">${{ totalAssetValue.toFixed(2) }}</div>
                        </div>
                        <v-icon x-large color="purple">mdi-currency-usd</v-icon>
                      </div>
                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>

              <!-- Search and Filter -->
              <v-card class="my-4" outlined>
                <v-card-text>
                  <v-row>
                    <v-col cols="12" md="3">
                      <v-text-field
                        v-model="search"
                        label="Search Assets"
                        prepend-icon="mdi-magnify"
                        clearable
                        hide-details
                      ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="3">
                      <v-select
                        v-model="selectedStatus"
                        :items="statusOptions"
                        label="Status"
                        prepend-icon="mdi-filter-variant"
                        clearable
                        hide-details
                      ></v-select>
                    </v-col>
                    
                    <v-col cols="12" md="3">
                      <v-select
                        v-model="selectedType"
                        :items="typeOptions"
                        label="Asset Type"
                        prepend-icon="mdi-shape"
                        clearable
                        hide-details
                      ></v-select>
                    </v-col>
                    
                    <v-col cols="12" md="3">
                      <v-menu
                        ref="dateMenu"
                        v-model="dateMenu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="dateRange"
                            label="Submission Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            clearable
                            hide-details
                            @click:clear="clearDateRange"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="dates"
                          range
                          @input="updateDateRange"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <!-- Assets Data Table -->
              <v-data-table
                :headers="assetHeaders"
                :items="filteredAssets"
                :search="search"
                :loading="loading"
                :items-per-page="10"
                :footer-props="{
                  'items-per-page-options': [10, 20, 50, -1],
                  'items-per-page-text': 'Assets per page'
                }"
                class="elevation-1"
              >
                <!-- Asset ID Column -->
                <template v-slot:item.id="{ item }">
                  <div class="font-weight-bold">{{ item.id }}</div>
                </template>
                
                <!-- Asset Type Column -->
                <template v-slot:item.type="{ item }">
                  <v-chip
                    :color="getAssetTypeColor(item.type)"
                    small
                    dark
                  >
                    {{ item.type }}
                  </v-chip>
                </template>
                
                <!-- Asset Owner Column -->
                <template v-slot:item.owner="{ item }">
                  <div class="d-flex align-center">
                    <v-avatar size="30" class="mr-2">
                      <v-img :src="item.ownerAvatar || 'https://cdn.vuetifyjs.com/images/john.jpg'"></v-img>
                    </v-avatar>
                    <span>{{ item.owner }}</span>
                  </div>
                </template>
                
                <!-- Estimated Value Column -->
                <template v-slot:item.estimatedValue="{ item }">
                  ${{ item.estimatedValue.toFixed(2) }}
                </template>
                
                <!-- Market Value Column -->
                <template v-slot:item.marketValue="{ item }">
                  <div v-if="item.marketValue">
                    ${{ item.marketValue.toFixed(2) }}
                  </div>
                  <v-btn 
                    v-else 
                    x-small 
                    outlined 
                    color="primary"
                    @click="openMarketValueDialog(item)"
                  >
                    Set Value
                  </v-btn>
                </template>

                <!-- Final Value Column -->
                <template v-slot:item.finalValue="{ item }">
                  <div v-if="item.finalValue">
                    ${{ item.finalValue.toFixed(2) }}
                  </div>
                  <v-btn 
                    v-else-if="item.marketValue" 
                    x-small 
                    outlined 
                    color="success"
                    @click="openFinalValueDialog(item)"
                  >
                    Set Value
                  </v-btn>
                  <div v-else class="grey--text">Pending Market Value</div>
                </template>
                
                <!-- Date Submitted Column -->
                <template v-slot:item.dateSubmitted="{ item }">
                  {{ formatDate(item.dateSubmitted) }}
                </template>
                
                <!-- Status Column -->
                <template v-slot:item.status="{ item }">
                  <v-chip
                    :color="getStatusColor(item.status)"
                    small
                  >
                    {{ item.status }}
                  </v-chip>
                </template>
                
                <!-- Actions Column -->
                <template v-slot:item.actions="{ item }">
                  <v-btn
                    icon
                    small
                    class="mr-1"
                    color="primary"
                    @click="viewAssetDetails(item)"
                  >
                    <v-icon small>mdi-eye</v-icon>
                  </v-btn>
                  
                  <v-btn
                    icon
                    small
                    class="mr-1"
                    color="warning"
                    @click="editAsset(item)"
                  >
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                  
                  <v-btn
                    icon
                    small
                    color="error"
                    @click="confirmDeleteAsset(item)"
                  >
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      
      <!-- Asset Details Dialog -->
      <v-dialog v-model="detailsDialog" max-width="900">
        <v-card v-if="selectedAsset">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Asset Details - {{ selectedAsset.id }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="detailsDialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
          
          <v-card-text class="pa-4">
            <v-row>
              <!-- Asset Images -->
              <v-col cols="12" md="6">
                <v-carousel height="300">
                  <v-carousel-item
                    v-for="(image, i) in selectedAsset.images || defaultImages"
                    :key="i"
                    :src="image"
                  ></v-carousel-item>
                </v-carousel>
              </v-col>
              
              <!-- Asset Information -->
              <v-col cols="12" md="6">
                <h3 class="text-h6 mb-3">Asset Information</h3>
                
                <v-simple-table>
                  <template v-slot:default>
                    <tbody>
                      <tr>
                        <td class="font-weight-bold">Asset ID</td>
                        <td>{{ selectedAsset.id }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Type</td>
                        <td>
                          <v-chip :color="getAssetTypeColor(selectedAsset.type)" small dark>
                            {{ selectedAsset.type }}
                          </v-chip>
                        </td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Owner</td>
                        <td>{{ selectedAsset.owner }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Description</td>
                        <td>{{ selectedAsset.description }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Condition</td>
                        <td>{{ selectedAsset.condition }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Year of Purchase</td>
                        <td>{{ selectedAsset.yearOfPurchase }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Date Submitted</td>
                        <td>{{ formatDate(selectedAsset.dateSubmitted) }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Status</td>
                        <td>
                          <v-chip :color="getStatusColor(selectedAsset.status)" small>
                            {{ selectedAsset.status }}
                          </v-chip>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
            
            <v-divider class="my-4"></v-divider>
            
            <!-- Valuation Information -->
            <v-row>
              <v-col cols="12">
                <h3 class="text-h6 mb-3">Valuation Information</h3>
                
                <v-simple-table>
                  <template v-slot:default>
                    <tbody>
                      <tr>
                        <td class="font-weight-bold" width="30%">Estimated Value (by Customer)</td>
                        <td>${{ selectedAsset.estimatedValue.toFixed(2) }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Market Value</td>
                        <td>
                          <div v-if="selectedAsset.marketValue">
                            ${{ selectedAsset.marketValue.toFixed(2) }}
                            <v-btn x-small text color="primary" class="ml-2" @click="openMarketValueDialog(selectedAsset)">
                              Edit
                            </v-btn>
                          </div>
                          <v-btn v-else small outlined color="primary" @click="openMarketValueDialog(selectedAsset)">
                            Set Market Value
                          </v-btn>
                        </td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Market Value Evaluation Date</td>
                        <td>{{ selectedAsset.marketValueDate ? formatDate(selectedAsset.marketValueDate) : 'Not evaluated yet' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Market Value Evaluator</td>
                        <td>{{ selectedAsset.marketValueEvaluator || 'Not assigned' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Final Value</td>
                        <td>
                          <div v-if="selectedAsset.finalValue">
                            ${{ selectedAsset.finalValue.toFixed(2) }}
                            <v-btn x-small text color="primary" class="ml-2" @click="openFinalValueDialog(selectedAsset)">
                              Edit
                            </v-btn>
                          </div>
                          <v-btn 
                            v-else-if="selectedAsset.marketValue" 
                            small 
                            outlined 
                            color="success" 
                            @click="openFinalValueDialog(selectedAsset)"
                          >
                            Set Final Value
                          </v-btn>
                          <div v-else class="grey--text">Pending Market Value Evaluation</div>
                        </td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Final Value Evaluation Date</td>
                        <td>{{ selectedAsset.finalValueDate ? formatDate(selectedAsset.finalValueDate) : 'Not evaluated yet' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Final Value Evaluator</td>
                        <td>{{ selectedAsset.finalValueEvaluator || 'Not assigned' }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
            
            <v-divider class="my-4"></v-divider>
            
            <!-- Asset-Specific Fields -->
            <v-row v-if="selectedAsset.type === 'Vehicles'">
              <v-col cols="12">
                <h3 class="text-h6 mb-3">Vehicle Information</h3>
                
                <v-simple-table>
                  <template v-slot:default>
                    <tbody>
                      <tr>
                        <td class="font-weight-bold" width="30%">Make</td>
                        <td>{{ selectedAsset.vehicleDetails?.make || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Model</td>
                        <td>{{ selectedAsset.vehicleDetails?.model || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">VIN</td>
                        <td>{{ selectedAsset.vehicleDetails?.vin || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Registration Number</td>
                        <td>{{ selectedAsset.vehicleDetails?.registrationNumber || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Mileage</td>
                        <td>{{ selectedAsset.vehicleDetails?.mileage?.toLocaleString() || '-' }} km</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Color</td>
                        <td>{{ selectedAsset.vehicleDetails?.color || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Engine Number</td>
                        <td>{{ selectedAsset.vehicleDetails?.engineNumber || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Chassis Number</td>
                        <td>{{ selectedAsset.vehicleDetails?.chassisNumber || '-' }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
            
            <!-- Document Attachments -->
            <v-row>
              <v-col cols="12">
                <h3 class="text-h6 mb-3">Supporting Documents</h3>
                
                <v-list v-if="selectedAsset.documents && selectedAsset.documents.length > 0">
                  <v-list-item
                    v-for="(doc, index) in selectedAsset.documents"
                    :key="index"
                  >
                    <v-list-item-icon>
                      <v-icon>{{ getDocumentIcon(doc.type) }}</v-icon>
                    </v-list-item-icon>
                    
                    <v-list-item-content>
                      <v-list-item-title>{{ doc.name }}</v-list-item-title>
                      <v-list-item-subtitle>{{ formatFileSize(doc.size) }}</v-list-item-subtitle>
                    </v-list-item-content>
                    
                    <v-list-item-action>
                      <v-btn icon color="primary" @click="downloadDocument(doc)">
                        <v-icon>mdi-download</v-icon>
                      </v-btn>
                    </v-list-item-action>
                  </v-list-item>
                </v-list>
                
                <v-alert
                  v-else
                  type="info"
                  outlined
                  text
                  dense
                >
                  No supporting documents attached to this asset.
                </v-alert>
              </v-col>
            </v-row>
            
            <!-- Asset History -->
            <v-row>
              <v-col cols="12">
                <h3 class="text-h6 mb-3">Asset History</h3>
                
                <v-timeline dense>
                  <v-timeline-item
                    v-for="(event, index) in selectedAsset.history || []"
                    :key="index"
                    :color="getHistoryItemColor(event.type)"
                    small
                  >
                    <div class="d-flex justify-space-between">
                      <strong>{{ event.action }}</strong>
                      <span class="grey--text text--darken-1">{{ formatDate(event.date) }}</span>
                    </div>
                    <div>{{ event.description }}</div>
                    <div class="grey--text">By: {{ event.user }}</div>
                  </v-timeline-item>
                  
                  <v-timeline-item
                    v-if="!selectedAsset.history || selectedAsset.history.length === 0"
                    color="grey"
                    small
                  >
                    <div>No history records found for this asset.</div>
                  </v-timeline-item>
                </v-timeline>
              </v-col>
            </v-row>
          </v-card-text>
          
          <v-card-actions class="pa-4">
            <v-btn
              color="primary"
              outlined
              @click="viewAssetOwner(selectedAsset)"
            >
              <v-icon left>mdi-account</v-icon>
              View Owner
            </v-btn>
            
            <v-spacer></v-spacer>
            
            <v-btn
              color="error"
              outlined
              @click="confirmDeleteAsset(selectedAsset)"
            >
              <v-icon left>mdi-delete</v-icon>
              Delete Asset
            </v-btn>
            
            <v-btn
              color="warning"
              outlined
              class="ml-2"
              @click="editAsset(selectedAsset)"
            >
              <v-icon left>mdi-pencil</v-icon>
              Edit Asset
            </v-btn>
            
            <v-btn
              color="success"
              class="ml-2"
              dark
              @click="updateAssetStatus(selectedAsset)"
            >
              <v-icon left>mdi-check</v-icon>
              Update Status
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      
      <!-- Market Value Evaluation Dialog -->
      <v-dialog v-model="marketValueDialog" max-width="500">
        <v-card>
          <v-card-title class="primary white--text">
            Market Value Evaluation
          </v-card-title>
          
          <v-card-text class="pa-4 mt-2">
            <p class="mb-2">Asset: <strong>{{ selectedAsset?.id }} - {{ selectedAsset?.description }}</strong></p>
            <p class="mb-4">Customer's Estimated Value: <strong>${{ selectedAsset?.estimatedValue.toFixed(2) }}</strong></p>
            
            <v-form ref="marketValueForm" v-model="marketValueFormValid">
              <v-text-field
                v-model.number="marketValueForm.value"
                label="Market Value"
                prefix="$"
                type="number"
                :rules="[v => !!v || 'Market value is required', v => v > 0 || 'Value must be greater than 0']"
                required
              ></v-text-field>
              
              <v-textarea
                v-model="marketValueForm.comments"
                label="Evaluation Comments"
                rows="3"
                :rules="[v => !!v || 'Comments are required']"
                required
              ></v-textarea>
            </v-form>
          </v-card-text>
          
          <v-card-actions class="pa-4">
            <v-spacer></v-spacer>
            <v-btn text @click="marketValueDialog = false">Cancel</v-btn>
            <v-btn 
              color="primary" 
              :disabled="!marketValueFormValid"
              @click="saveMarketValue"
            >
              Save Evaluation
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      
      <!-- Final Value Evaluation Dialog -->
      <v-dialog v-model="finalValueDialog" max-width="500">
        <v-card>
          <v-card-title class="success white--text">
            Final Value Evaluation
          </v-card-title>
          
          <v-card-text class="pa-4 mt-2">
            <p class="mb-2">Asset: <strong>{{ selectedAsset?.id }} - {{ selectedAsset?.description }}</strong></p>
            <p class="mb-2">Customer's Estimated Value: <strong>${{ selectedAsset?.estimatedValue.toFixed(2) }}</strong></p>
            <p class="mb-4">Market Value: <strong>${{ selectedAsset?.marketValue.toFixed(2) }}</strong></p>
            
            <v-form ref="finalValueForm" v-model="finalValueFormValid">
              <v-text-field
                v-model.number="finalValueForm.value"
                label="Final Value"
                prefix="$"
                type="number"
                :rules="[v => !!v || 'Final value is required', v => v > 0 || 'Value must be greater than 0']"
                required
              ></v-text-field>
              
              <v-textarea
                v-model="finalValueForm.comments"
                label="Evaluation Comments"
                rows="3"
                :rules="[v => !!v || 'Comments are required']"
                required
              ></v-textarea>
            </v-form>
          </v-card-text>
          
          <v-card-actions class="pa-4">
            <v-spacer></v-spacer>
            <v-btn text @click="finalValueDialog = false">Cancel</v-btn>
            <v-btn 
              color="success" 
              :disabled="!finalValueFormValid"
              @click="saveFinalValue"
            >
              Save Evaluation
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      
      <!-- Update Status Dialog -->
      <v-dialog v-model="statusDialog" max-width="400">
        <v-card>
          <v-card-title class="primary white--text">
            Update Asset Status
          </v-card-title>
          
          <v-card-text class="pa-4 mt-2">
            <p class="mb-4">Asset: <strong>{{ selectedAsset?.id }} - {{ selectedAsset?.description }}</strong></p>
            
            <v-select
              v-model="selectedStatus"
              :items="statusOptions"
              label="Select New Status"
              outlined
              required
            ></v-select>
            
            <v-textarea
              v-model="statusChangeComment"
              label="Comments"
              rows="3"
              placeholder="Add comments about this status change"
            ></v-textarea>
          </v-card-text>
          
          <v-card-actions class="pa-4">
            <v-spacer></v-spacer>
            <v-btn text @click="statusDialog = false">Cancel</v-btn>
            <v-btn 
              color="primary"
              @click="saveStatusChange"
            >
              Update Status
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      
      <!-- Delete Confirmation Dialog -->
      <v-dialog v-model="deleteDialog" max-width="400">
        <v-card>
          <v-card-title class="error white--text">
            Confirm Delete
          </v-card-title>
          
          <v-card-text class="pa-4 mt-2">
            <p>Are you sure you want to delete this asset?</p>
            <p class="font-weight-bold">Asset ID: {{ selectedAsset?.id }}</p>
            <p class="font-weight-bold">Description: {{ selectedAsset?.description }}</p>
            <p class="red--text">This action cannot be undone.</p>
          </v-card-text>
          
          <v-card-actions class="pa-4">
            <v-spacer></v-spacer>
            <v-btn text @click="deleteDialog = false">Cancel</v-btn>
            <v-btn 
              color="error"
              @click="deleteAsset"
            >
              Delete Asset
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
  name: 'AssetManagement',
  layout: SuperAdminLayout,
  
  data() {
    return {
      // Table headers
      assetHeaders: [
        { text: 'Asset ID', value: 'id', sortable: true },
        { text: 'Type', value: 'type', sortable: true },
        { text: 'Description', value: 'description', sortable: true },
        { text: 'Owner', value: 'owner', sortable: true },
        { text: 'Estimated Value', value: 'estimatedValue', sortable: true },
        { text: 'Market Value', value: 'marketValue', sortable: true },
        { text: 'Final Value', value: 'finalValue', sortable: true },
        { text: 'Date Submitted', value: 'dateSubmitted', sortable: true },
        { text: 'Status', value: 'status', sortable: true },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      
      // Search & Filter
      search: '',
      selectedStatus: null,
      selectedType: null,
      dateMenu: false,
      dates: [],
      dateRange: '',
      
      // Data loading state
      loading: false,
      
      // Dialogs
      detailsDialog: false,
      marketValueDialog: false,
      finalValueDialog: false,
      statusDialog: false,
      deleteDialog: false,
      
      // Selected asset for operations
      selectedAsset: null,
      
      // Form models
      marketValueForm: {
        value: 0,
        comments: ''
      },
      finalValueForm: {
        value: 0,
        comments: ''
      },
      marketValueFormValid: false,
      finalValueFormValid: false,
      
      // Status change
      statusChangeComment: '',
      
      // Filter options
      statusOptions: [
        'Active',
        'Pending Evaluation',
        'Evaluation Complete',
        'In Loan',
        'Redeemed',
        'Defaulted',
        'Auctioned',
        'Removed'
      ],
      
      typeOptions: [
        'Vehicles',
        'Jewelry',
        'Electronics',
        'Collectibles',
        'Furniture',
        'Other'
      ],
      
      // Default images for preview
      defaultImages: [
        'https://via.placeholder.com/500x300?text=No+Image+Available',
        'https://via.placeholder.com/500x300?text=No+Image+Available'
      ],
      
      // Mock asset data (would come from API in real implementation)
      assets: [
        {
          id: 'A001',
          type: 'Electronics',
          description: 'MacBook Pro 2023',
          owner: 'John Smith',
          ownerAvatar: 'https://cdn.vuetifyjs.com/images/john.jpg',
          estimatedValue: 1200,
          marketValue: 1000,
          finalValue: 950,
          dateSubmitted: '2025-05-01',
          status: 'Active',
          condition: 'Like New',
          yearOfPurchase: 2023,
          marketValueDate: '2025-05-03',
          marketValueEvaluator: 'Michael Brown',
          finalValueDate: '2025-05-04',
          finalValueEvaluator: 'Sarah Johnson',
          images: [
            'https://via.placeholder.com/500x300?text=MacBook+Pro+Front',
            'https://via.placeholder.com/500x300?text=MacBook+Pro+Side',
            'https://via.placeholder.com/500x300?text=MacBook+Pro+Back'
          ],
          documents: [
            { name: 'Purchase Receipt.pdf', type: 'pdf', size: 1024000 },
            { name: 'Warranty Card.jpg', type: 'image', size: 2048000 }
          ],
          history: [
            { 
              date: '2025-05-01', 
              action: 'Asset Submitted', 
              description: 'Asset was submitted for evaluation.',
              user: 'John Smith (Customer)',
              type: 'submission'
            },
            { 
              date: '2025-05-03', 
              action: 'Market Value Evaluation', 
              description: 'Market value evaluated at $1,000.00',
              user: 'Michael Brown (Evaluator)',
              type: 'market_valuation'
            },
            { 
              date: '2025-05-04', 
              action: 'Final Value Evaluation', 
              description: 'Final value evaluated at $950.00',
              user: 'Sarah Johnson (Loan Officer)',
              type: 'final_valuation'
            }
          ]
        },
        {
          id: 'A002',
          type: 'Jewelry',
          description: 'Gold Bracelet',
          owner: 'Emily Davis',
          ownerAvatar: null,
          estimatedValue: 800,
          marketValue: 750,
          finalValue: null,
          dateSubmitted: '2025-05-05',
          status: 'Pending Evaluation',
          condition: 'Used',
          yearOfPurchase: 2020,
          marketValueDate: '2025-05-07',
          marketValueEvaluator: 'Michael Brown',
          images: [
            'https://via.placeholder.com/500x300?text=Gold+Bracelet+1',
            'https://via.placeholder.com/500x300?text=Gold+Bracelet+2'
          ],
          documents: [
            { name: 'Jewelry Certificate.pdf', type: 'pdf', size: 512000 }
          ],
          history: [
            { 
              date: '2025-05-05', 
              action: 'Asset Submitted', 
              description: 'Asset was submitted for evaluation.',
              user: 'Emily Davis (Customer)',
              type: 'submission'
            },
            { 
              date: '2025-05-07', 
              action: 'Market Value Evaluation', 
              description: 'Market value evaluated at $750.00',
              user: 'Michael Brown (Evaluator)',
              type: 'market_valuation'
            }
          ]
        },
        {
          id: 'A003',
          type: 'Vehicles',
          description: 'Toyota Camry 2022',
          owner: 'Robert Wilson',
          ownerAvatar: null,
          estimatedValue: 18000,
          marketValue: null,
          finalValue: null,
          dateSubmitted: '2025-05-08',
          status: 'Pending Evaluation',
          condition: 'Good',
          yearOfPurchase: 2022,
          vehicleDetails: {
            make: 'Toyota',
            model: 'Camry',
            vin: 'JT2BF22K1W0123456',
            registrationNumber: 'ABC-1234',
            mileage: 25000,
            color: 'Silver',
            engineNumber: 'EN12345678',
            chassisNumber: 'CH12345678'
          },
          images: [
            'https://via.placeholder.com/500x300?text=Toyota+Camry+Front',
            'https://via.placeholder.com/500x300?text=Toyota+Camry+Side',
            'https://via.placeholder.com/500x300?text=Toyota+Camry+Back',
            'https://via.placeholder.com/500x300?text=Toyota+Camry+Interior'
          ],
          documents: [
            { name: 'Vehicle Registration.pdf', type: 'pdf', size: 1536000 },
            { name: 'Insurance Document.pdf', type: 'pdf', size: 2048000 },
            { name: 'Service History.pdf', type: 'pdf', size: 3072000 }
          ],
          history: [
            { 
              date: '2025-05-08', 
              action: 'Asset Submitted', 
              description: 'Asset was submitted for evaluation.',
              user: 'Robert Wilson (Customer)',
              type: 'submission'
            }
          ]
        }
      ]
    };
  },
  
  computed: {
    // Stats for dashboard
    pendingEvaluationCount() {
      return this.assets.filter(asset => asset.status === 'Pending Evaluation').length;
    },
    
    activeAssetsCount() {
      return this.assets.filter(asset => asset.status === 'Active').length;
    },
    
    totalAssetValue() {
      // Calculate based on final value if available, otherwise market value, otherwise estimated value
      return this.assets.reduce((total, asset) => {
        if (asset.finalValue) return total + asset.finalValue;
        if (asset.marketValue) return total + asset.marketValue;
        return total + asset.estimatedValue;
      }, 0);
    },
    
    // Filtered assets based on search and filter criteria
    filteredAssets() {
      let filtered = [...this.assets];
      
      // Filter by status if selected
      if (this.selectedStatus) {
        filtered = filtered.filter(asset => asset.status === this.selectedStatus);
      }
      
      // Filter by type if selected
      if (this.selectedType) {
        filtered = filtered.filter(asset => asset.type === this.selectedType);
      }
      
      // Filter by date range if selected
      if (this.dates && this.dates.length === 2) {
        const startDate = new Date(this.dates[0]).getTime();
        const endDate = new Date(this.dates[1]).getTime();
        
        filtered = filtered.filter(asset => {
          const assetDate = new Date(asset.dateSubmitted).getTime();
          return assetDate >= startDate && assetDate <= endDate;
        });
      }
      
      return filtered;
    }
  },
  
  methods: {
    // Format date for display
    formatDate(dateString) {
      try {
        return format(new Date(dateString), 'MMM dd, yyyy');
      } catch (e) {
        return dateString || 'N/A';
      }
    },
    
    // Get color for asset type chip
    getAssetTypeColor(type) {
      switch (type) {
        case 'Vehicles': return 'blue';
        case 'Jewelry': return 'amber';
        case 'Electronics': return 'teal';
        case 'Collectibles': return 'purple';
        case 'Furniture': return 'brown';
        default: return 'grey';
      }
    },
    
    // Get color for status chip
    getStatusColor(status) {
      switch (status) {
        case 'Active': return 'success';
        case 'Pending Evaluation': return 'warning';
        case 'Evaluation Complete': return 'info';
        case 'In Loan': return 'primary';
        case 'Redeemed': return 'success';
        case 'Defaulted': return 'error';
        case 'Auctioned': return 'purple';
        case 'Removed': return 'grey';
        default: return 'grey';
      }
    },
    
    // Get color for history timeline items
    getHistoryItemColor(type) {
      switch (type) {
        case 'submission': return 'primary';
        case 'market_valuation': return 'amber';
        case 'final_valuation': return 'success';
        case 'loan_created': return 'blue';
        case 'loan_repaid': return 'green';
        case 'status_change': return 'purple';
        default: return 'grey';
      }
    },
    
    // Get icon for document type
    getDocumentIcon(type) {
      switch (type) {
        case 'pdf': return 'mdi-file-pdf-box';
        case 'image': return 'mdi-file-image';
        case 'doc': return 'mdi-file-word';
        case 'xls': return 'mdi-file-excel';
        default: return 'mdi-file-document';
      }
    },
    
    // Format file size for display
    formatFileSize(bytes) {
      if (bytes < 1024) return bytes + ' B';
      if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },
    
    // Date range picker methods
    updateDateRange() {
      if (this.dates.length === 2) {
        const [start, end] = this.dates;
        this.dateRange = `${format(new Date(start), 'MMM dd, yyyy')} - ${format(new Date(end), 'MMM dd, yyyy')}`;
        this.dateMenu = false;
      }
    },
    
    clearDateRange() {
      this.dates = [];
      this.dateRange = '';
    },
    
    // View asset details
    viewAssetDetails(asset) {
      this.selectedAsset = asset;
      this.detailsDialog = true;
    },
    
    // Open market value dialog
    openMarketValueDialog(asset) {
      this.selectedAsset = asset;
      this.marketValueForm.value = asset.marketValue || Math.floor(asset.estimatedValue * 0.8); // Default to 80% of estimated value
      this.marketValueForm.comments = '';
      this.marketValueDialog = true;
    },
    
    // Save market value
    saveMarketValue() {
      if (!this.$refs.marketValueForm.validate()) return;
      
      // Update the asset with market value
      const index = this.assets.findIndex(a => a.id === this.selectedAsset.id);
      if (index !== -1) {
        this.assets[index].marketValue = this.marketValueForm.value;
        this.assets[index].marketValueDate = new Date().toISOString().split('T')[0];
        this.assets[index].marketValueEvaluator = 'Current User'; // This would be the logged-in user
        
        // Add to history
        if (!this.assets[index].history) this.assets[index].history = [];
        this.assets[index].history.push({
          date: new Date().toISOString().split('T')[0],
          action: 'Market Value Evaluation',
          description: `Market value evaluated at $${this.marketValueForm.value.toFixed(2)}. Comments: ${this.marketValueForm.comments}`,
          user: 'Current User (Evaluator)',
          type: 'market_valuation'
        });
        
        // Update selected asset reference
        this.selectedAsset = { ...this.assets[index] };
      }
      
      this.marketValueDialog = false;
      
      // Show success message
      this.$nextTick(() => {
        // This would be replaced with your app's notification system
        alert('Market value saved successfully!');
      });
    },
    
    // Open final value dialog
    openFinalValueDialog(asset) {
      this.selectedAsset = asset;
      this.finalValueForm.value = asset.finalValue || asset.marketValue; // Default to market value
      this.finalValueForm.comments = '';
      this.finalValueDialog = true;
    },
    
    // Save final value
    saveFinalValue() {
      if (!this.$refs.finalValueForm.validate()) return;
      
      // Update the asset with final value
      const index = this.assets.findIndex(a => a.id === this.selectedAsset.id);
      if (index !== -1) {
        this.assets[index].finalValue = this.finalValueForm.value;
        this.assets[index].finalValueDate = new Date().toISOString().split('T')[0];
        this.assets[index].finalValueEvaluator = 'Current User'; // This would be the logged-in user
        
        // Update status to reflect evaluation complete
        this.assets[index].status = 'Evaluation Complete';
        
        // Add to history
        if (!this.assets[index].history) this.assets[index].history = [];
        this.assets[index].history.push({
          date: new Date().toISOString().split('T')[0],
          action: 'Final Value Evaluation',
          description: `Final value evaluated at $${this.finalValueForm.value.toFixed(2)}. Comments: ${this.finalValueForm.comments}`,
          user: 'Current User (Loan Officer)',
          type: 'final_valuation'
        });
        
        // Update selected asset reference
        this.selectedAsset = { ...this.assets[index] };
      }
      
      this.finalValueDialog = false;
      
      // Show success message
      this.$nextTick(() => {
        // This would be replaced with your app's notification system
        alert('Final value saved successfully!');
      });
    },
    
    // Methods for status management
    updateAssetStatus(asset) {
      this.selectedAsset = asset;
      this.selectedStatus = asset.status;
      this.statusChangeComment = '';
      this.statusDialog = true;
    },
    
    saveStatusChange() {
      const index = this.assets.findIndex(a => a.id === this.selectedAsset.id);
      if (index !== -1) {
        const oldStatus = this.assets[index].status;
        this.assets[index].status = this.selectedStatus;
        
        // Add to history
        if (!this.assets[index].history) this.assets[index].history = [];
        this.assets[index].history.push({
          date: new Date().toISOString().split('T')[0],
          action: 'Status Updated',
          description: `Status changed from "${oldStatus}" to "${this.selectedStatus}". ${this.statusChangeComment ? 'Comments: ' + this.statusChangeComment : ''}`,
          user: 'Current User (Admin)',
          type: 'status_change'
        });
        
        // Update selected asset reference
        this.selectedAsset = { ...this.assets[index] };
      }
      
      this.statusDialog = false;
      
      // Show success message
      this.$nextTick(() => {
        // This would be replaced with your app's notification system
        alert('Status updated successfully!');
      });
    },
    
    // Methods for asset management
    editAsset(asset) {
      this.selectedAsset = asset;
      // In a real app, you would navigate to an edit form or open an edit dialog
      alert(`Edit asset ${asset.id} - This would open an edit form in the real application`);
    },
    
    confirmDeleteAsset(asset) {
      this.selectedAsset = asset;
      this.deleteDialog = true;
    },
    
    deleteAsset() {
      const index = this.assets.findIndex(a => a.id === this.selectedAsset.id);
      if (index !== -1) {
        this.assets.splice(index, 1);
      }
      
      this.deleteDialog = false;
      if (this.detailsDialog) this.detailsDialog = false;
      
      // Show success message
      this.$nextTick(() => {
        // This would be replaced with your app's notification system
        alert('Asset deleted successfully!');
      });
    },
    
    viewAssetOwner(asset) {
      // In a real app, you would navigate to the customer profile
      alert(`View customer profile for ${asset.owner} - This would navigate to the customer profile in the real application`);
    },
    
    downloadDocument(doc) {
      // In a real app, this would trigger a download
      alert(`Downloading ${doc.name} - This would initiate a file download in the real application`);
    }
  }
};
</script>