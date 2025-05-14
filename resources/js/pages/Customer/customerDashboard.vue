<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      app
      dark
      color="primary"
    >
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="text-h6 white--text">
            Design Media Pawn
          </v-list-item-title>
          <v-list-item-subtitle class="white--text">
            User Dashboard
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item
          v-for="item in menuItems"
          :key="item.title"
          link
          @click="activeTab = item.value"
          :class="activeTab === item.value ? 'v-list-item--active' : ''"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      
      <template v-slot:append>
        <v-list-item link @click="logout">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>
          
          <v-list-item-content>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="primary"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Design Media Pawn System</v-toolbar-title>
      <v-spacer></v-spacer>
      
      <v-menu
        left
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            icon
            v-bind="attrs"
            v-on="on"
          >
            <v-badge
              :content="unreadNotifications.length"
              :value="unreadNotifications.length"
              color="error"
              overlap
            >
              <v-icon>mdi-bell</v-icon>
            </v-badge>
          </v-btn>
        </template>

        <v-list width="320">
          <v-list-item-group>
            <v-subheader>Notifications</v-subheader>
            <v-list-item
              v-for="notification in notifications"
              :key="notification.id"
              :class="!notification.read ? 'light-blue lighten-5' : ''"
            >
              <v-list-item-content>
                <v-list-item-title v-text="notification.message"></v-list-item-title>
                <v-list-item-subtitle v-text="notification.date"></v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-action>
                <v-icon v-if="!notification.read" color="primary">mdi-circle-small</v-icon>
              </v-list-item-action>
            </v-list-item>
          </v-list-item-group>
          <v-divider></v-divider>
          <v-list-item link class="text-center">
            <v-list-item-title class="text-center blue--text">View All Notifications</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      
      <v-menu
        left
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            icon
            v-bind="attrs"
            v-on="on"
          >
            <v-icon>mdi-account-circle</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>mdi-account</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Profile</v-list-item-title>
          </v-list-item>
          
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>mdi-cog</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item>
          
          <v-divider></v-divider>
          
          <v-list-item link @click="logout">
            <v-list-item-icon>
              <v-icon>mdi-logout</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <!-- User personal details card -->
        <v-card class="mb-6" outlined>
          <v-card-title>
            <v-icon left>mdi-account</v-icon>
            Personal Details
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12" sm="4">
                <strong>Name:</strong> {{ userData.name }}
              </v-col>
              <v-col cols="12" sm="4">
                <strong>Email:</strong> {{ userData.email }}
              </v-col>
              <v-col cols="12" sm="4">
                <strong>Phone:</strong> {{ userData.phone }}
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
        
        <!-- Stats cards row -->
        <v-row>
          <v-col cols="12" md="4">
            <v-card height="100">
              <v-card-text>
                <div class="d-flex justify-space-between align-center">
                  <div>
                    <div class="text-overline mb-1">Active Loans</div>
                    <div class="text-h4">{{ activeLoans.length }}</div>
                  </div>
                  <v-icon x-large color="primary">mdi-wallet</v-icon>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
          
          <v-col cols="12" md="4">
            <v-card height="100">
              <v-card-text>
                <div class="d-flex justify-space-between align-center">
                  <div>
                    <div class="text-overline mb-1">Assets</div>
                    <div class="text-h4">{{ assets.length }}</div>
                  </div>
                  <v-icon x-large color="success">mdi-package-variant-closed</v-icon>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
          
          <v-col cols="12" md="4">
            <v-card height="100">
              <v-card-text>
                <div class="d-flex justify-space-between align-center">
                  <div>
                    <div class="text-overline mb-1">Account Balance</div>
                    <div class="text-h4">${{ accountBalance.toFixed(2) }}</div>
                  </div>
                  <v-icon x-large color="purple">mdi-currency-usd</v-icon>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        
        <!-- Quick actions row -->
        <v-card class="my-6" outlined>
          <v-card-title>Quick Actions</v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="6" sm="3">
                <v-btn
                  block
                  color="primary"
                  class="py-6"
                  elevation="2"
                >
                  <v-icon left>mdi-package-variant-plus</v-icon>
                  Add New Asset
                </v-btn>
              </v-col>
              
              <v-col cols="6" sm="3">
                <v-btn
                  block
                  color="success"
                  class="py-6"
                  elevation="2"
                >
                  <v-icon left>mdi-cash-plus</v-icon>
                  Apply for Loan
                </v-btn>
              </v-col>
              
              <v-col cols="6" sm="3">
                <v-btn
                  block
                  color="purple"
                  class="py-6"
                  elevation="2"
                  dark
                >
                  <v-icon left>mdi-credit-card</v-icon>
                  Make Payment
                </v-btn>
              </v-col>
              
              <v-col cols="6" sm="3">
                <v-btn
                  block
                  color="warning"
                  class="py-6"
                  elevation="2"
                  dark
                >
                  <v-icon left>mdi-gavel</v-icon>
                  View Auctions
                </v-btn>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
        
        <!-- Main content area based on active tab -->
        <v-tabs
          v-model="contentTab"
          background-color="primary"
          dark
          grow
        >
          <v-tab value="loans">Active Loans</v-tab>
          <v-tab value="assets">Assets</v-tab>
          <v-tab value="transactions">Transaction History</v-tab>
        </v-tabs>
        
        <v-tabs-items v-model="contentTab">
          <!-- Loans Tab -->
          <v-tab-item value="loans">
            <v-card flat>
              <v-card-text>
                <v-data-table
                  :headers="loanHeaders"
                  :items="loans"
                  :items-per-page="5"
                  class="elevation-1"
                >
                  <template v-slot:item.status="{ item }">
                    <v-chip
                      :color="getLoanStatusColor(item.status)"
                      small
                    >
                      {{ item.status }}
                    </v-chip>
                  </template>
                  <template v-slot:item.actions="{ item }">
                    <v-btn
                      small
                      text
                      color="primary"
                      @click="viewLoanDetails(item)"
                    >
                      Details
                    </v-btn>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-tab-item>
          
          <!-- Assets Tab -->
          <v-tab-item value="assets">
            <v-card flat>
              <v-card-text>
                <v-data-table
                  :headers="assetHeaders"
                  :items="assets"
                  :items-per-page="5"
                  class="elevation-1"
                >
                  <template v-slot:item.status="{ item }">
                    <v-chip
                      :color="getAssetStatusColor(item.status)"
                      small
                    >
                      {{ item.status }}
                    </v-chip>
                  </template>
                  <template v-slot:item.estimatedValue="{ item }">
                    ${{ item.estimatedValue.toFixed(2) }}
                  </template>
                  <template v-slot:item.actions="{ item }">
                    <v-btn
                      small
                      text
                      color="primary"
                      @click="viewAssetDetails(item)"
                    >
                      Details
                    </v-btn>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-tab-item>
          
          <!-- Transactions Tab -->
          <v-tab-item value="transactions">
            <v-card flat>
              <v-card-text>
                <v-data-table
                  :headers="transactionHeaders"
                  :items="transactions"
                  :items-per-page="5"
                  class="elevation-1"
                >
                  <template v-slot:item.status="{ item }">
                    <v-chip
                      :color="getTransactionStatusColor(item.status)"
                      small
                    >
                      {{ item.status }}
                    </v-chip>
                  </template>
                  <template v-slot:item.amount="{ item }">
                    <span :class="item.amount > 0 ? 'success--text' : ''">
                      ${{ item.amount.toFixed(2) }}
                    </span>
                  </template>
                  <template v-slot:item.actions="{ item }">
                    <v-btn
                      small
                      text
                      color="primary"
                      @click="viewTransactionDetails(item)"
                    >
                      Details
                    </v-btn>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-container>
    </v-main>
    
    <v-footer app>
      <span>&copy; {{ new Date().getFullYear() }} Design Media Pawn System</span>
    </v-footer>
  </v-app>
</template>

<script>
import { router } from '@inertiajs/vue3'
export default {
  name: 'UserDashboard',
  
  data() {
    return {
      drawer: true,
      activeTab: 'dashboard',
      contentTab: 'loans',
      
      // Mock user data
      userData: {
        name: 'John Smith',
        email: 'john.smith@example.com',
        phone: '+263 71 234 5678'
      },
      
      accountBalance: 600.00,
      
      // Menu items for the navigation drawer
      menuItems: [
        { title: 'Dashboard', icon: 'mdi-view-dashboard', value: 'dashboard' },
        { title: 'My Assets', icon: 'mdi-package-variant-closed', value: 'assets' },
        { title: 'My Loans', icon: 'mdi-wallet', value: 'loans' },
        { title: 'Transactions', icon: 'mdi-swap-horizontal', value: 'transactions' },
        { title: 'Auctions', icon: 'mdi-gavel', value: 'auctions' },
        { title: 'Support', icon: 'mdi-help-circle', value: 'support' },
        { title: 'Settings', icon: 'mdi-cog', value: 'settings' }
      ],
      
      // Asset table headers
      assetHeaders: [
        { text: 'Asset ID', value: 'id' },
        { text: 'Type', value: 'type' },
        { text: 'Description', value: 'description' },
        { text: 'Estimated Value', value: 'estimatedValue' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      
      // Loan table headers
      loanHeaders: [
        { text: 'Loan ID', value: 'id' },
        { text: 'Amount', value: 'amount' },
        { text: 'Due Date', value: 'dueDate' },
        { text: 'Interest Rate', value: 'interestRate' },
        { text: 'Asset', value: 'asset' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      
      // Transaction table headers
      transactionHeaders: [
        { text: 'Transaction ID', value: 'id' },
        { text: 'Date', value: 'date' },
        { text: 'Type', value: 'type' },
        { text: 'Amount', value: 'amount' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      
      // Mock data for assets
      assets: [
        { id: 'A001', type: 'Electronics', description: 'MacBook Pro 2023', estimatedValue: 1200, status: 'Active' },
        { id: 'A002', type: 'Jewelry', description: 'Gold Bracelet', estimatedValue: 800, status: 'Pending Evaluation' }
      ],
      
      // Mock data for loans
      loans: [
        { id: 'L001', amount: 600, dueDate: '2025-06-15', status: 'Active', interestRate: '2%', asset: 'MacBook Pro 2023' },
        { id: 'L002', amount: 400, dueDate: '2025-07-10', status: 'Awaiting Approval', interestRate: '2%', asset: 'Gold Bracelet' }
      ],
      
      // Mock data for transactions
      transactions: [
        { id: 'T001', date: '2025-05-12', type: 'Loan Disbursement', amount: 600, status: 'Completed' },
        { id: 'T002', date: '2025-05-10', type: 'Asset Evaluation', amount: 0, status: 'Completed' },
        { id: 'T003', date: '2025-05-05', type: 'Loan Application', amount: 0, status: 'Completed' }
      ],
      
      // Mock data for notifications
      notifications: [
        { id: 'N001', date: '2025-05-13', message: 'Your loan L001 has been approved', read: false },
        { id: 'N002', date: '2025-05-11', message: 'Asset A001 has been evaluated', read: true },
        { id: 'N003', date: '2025-05-10', message: 'Welcome to Design Media Pawn System', read: true }
      ]
    }
  },
  
  computed: {
    // Get only active loans
    activeLoans() {
      return this.loans.filter(loan => loan.status === 'Active');
    },
    
    // Get unread notifications
    unreadNotifications() {
      return this.notifications.filter(notification => !notification.read);
    }
  },
  
  methods: {
    // Method to handle logout
    logout() {
    router.post('/logout', {}, {
        onSuccess: () => {
            window.location.reload()
        },
    });
},
    
    // Get color for loan status chip
    getLoanStatusColor(status) {
      if (status === 'Active') return 'primary';
      if (status === 'Awaiting Approval') return 'warning';
      if (status === 'Completed') return 'success';
      if (status === 'Overdue') return 'error';
      return 'grey';
    },
    
    // Get color for asset status chip
    getAssetStatusColor(status) {
      if (status === 'Active') return 'success';
      if (status === 'Pending Evaluation') return 'warning';
      if (status === 'In Loan') return 'primary';
      return 'grey';
    },
    
    // Get color for transaction status chip
    getTransactionStatusColor(status) {
      if (status === 'Completed') return 'success';
      if (status === 'Pending') return 'warning';
      if (status === 'Failed') return 'error';
      return 'grey';
    },
    
    // Methods for viewing details
    viewLoanDetails(loan) {
      console.log('View loan details:', loan);
      // Implement navigation to loan details page
    },
    
    viewAssetDetails(asset) {
      console.log('View asset details:', asset);
      // Implement navigation to asset details page
    },
    
    viewTransactionDetails(transaction) {
      console.log('View transaction details:', transaction);
      // Implement navigation to transaction details page
    }
  }
}
</script>