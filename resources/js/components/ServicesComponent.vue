<template>
    <div class="card">
        <div class="card-body">                        
            <div v-if="auth" >
                <div class="d-flex justify-content-between">
                    <h4>Services</h4>
                    <a @click.prevent="showNewServiceForm" href="">
                        <span v-show="!displayAddService" >New Service</span>
                        <span v-show="displayAddService" >Close</span>
                    </a>
                </div>                      
                <ServiceComponent @editService="editExistingService" @removeService="deleteService" v-show="displayServiceList" v-for="service in services" :key="service.id" :service="service" />
            </div>
            <div v-else>
                <div class="d-flex flex-column justify-content-between" >
                    <ServiceSearchComponent @filterByDistance="filterServices" @searchServices="searchService" />
                </div>
                <div v-if="loading"  class="d-flex justify-content-center" >
                    <img src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" alt="">
                </div>
                <div v-else >
                    <PublicServiceComponent v-for="service in filteredListDistance" :key="service.id" :service="service"  />
                </div>
            </div>
            <AddServiceComponent :editableService="editServiceObj" @newService="pushNewService" v-show="displayAddService" />
            <!-- <EditServiceComponent :editableService="editServiceObj"  v-show="displayEditService" /> -->
        </div>
    </div>
</template>

<script>
    import ServiceComponent from './ServiceComponent';
    import ServiceSearchComponent from './ServiceSearchComponent';
    import AddServiceComponent from './AddServiceComponent';
    import EditServiceComponent from './EditServiceComponent';
    import PublicServiceComponent from './PublicServiceComponent';

    export default {
        components: {
            ServiceComponent,
            PublicServiceComponent,
            AddServiceComponent,
            ServiceSearchComponent,
            EditServiceComponent
        },
        props: ['auth'],
        data: () => {
            return {
                editServiceObj: {},
                loading: true,
                userSelecteddDitance: '',
                userCoordObj: '',
                searchString: '',
                services: [],
                displayServiceList: true,
                displayAddService: false,
                displayEditService: false
            }
        }, 
        mounted() {
            axios.get('services')
            .then(response => (this.services = response.data))
            .then(this.loading = false);
        },
        computed: {
            filteredListDistance() {
                return this.services
                    .filter(service => {
                        return service.title.toLowerCase().includes(this.searchString)
                    }).filter(service => {
                        return (this.getDistanceFromLatLonInKm(
                                                   this.userCoordObj.latitude,
                                                   this.userCoordObj.longitude,
                                                   service.latitude,
                                                   service.longitude) <= this.userSelecteddDitance )                        
                })
            }            
        },        
        methods: {
            showNewServiceForm: function() {
                this.displayServiceList = !this.displayServiceList
                this.displayAddService = !this.displayAddService
            },
            showEditServiceForm: function() {
                this.displayServiceList = !this.displayServiceList
                this.displayEditService = !this.displayEditService
            },
            pushNewService(service) {
                this.services.push(service);
                alert('Service Added');
            },
            editExistingService: function(service) {

                this.editServiceObj = service;
                this.showNewServiceForm();
            },
            deleteService(service) {

                this.services = this.services.filter(function(value){

                    return value.id != service.id;

                });

                alert('Service Removed');
            },
            searchService: function(chars) {
                this.searchString = chars;
            },
            filterServices: function(userCoordObj, selectedDitance) {

                this.userCoordObj = userCoordObj;
                this.userSelecteddDitance = selectedDitance;

            },
            getDistanceFromLatLonInKm: function(lat1,lon1,lat2,lon2) {
                var R = 6371; // Radius of the earth in km
                var dLat = this.deg2rad(lat2-lat1); 
                var dLon = this.deg2rad(lon2-lon1); 
                var a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2)
                    ; 
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                var d = R * c; // Distance in km
                return d;
            },
            deg2rad: function(deg) {
                return deg * (Math.PI/180)
            }            
        }
    }
</script>