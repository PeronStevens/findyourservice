<template>
    <div class="mb-4" >
        <div class="d-flex mb-2" >
            <input v-model="userSearchString" class="form-control" type="text" placeholder="search services">
            <button @click="sendSearch" class="ml-2 text-white btn btn-info" >Submit</button>
        </div>
        <div v-if="geoEnabled" >
            <select @change="showServicesWithinDistance" v-model="selectedDistance">
                <option disabled value="">Please select one</option>
                <option v-for="distance in distances" :key="distance" v-text="distance" >
                </option>
            </select>
            <span> in km</span>
        </div>
        <div v-else >
            <span>Location set to "Anywhere"</span>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            userSearchString: '',
            selectedDistance: '',
            geoEnabled: false,
            distances: [1,2,5,10,25,50,100],
            userCoords: {
                latitude: 0,
                longitude: 0
            }
        }
    },
    mounted() {
        this.getLocation();  
    },
    methods: {
        sendSearch: function() {
            this.$emit('searchServices', this.userSearchString)
        },
        getLocation: function() {

            if (navigator.geolocation) {
                
                navigator.geolocation.getCurrentPosition(this.returnPosition);
                this.$emit('filterByDistance', this.userCoords, 99999);
              
            }       
        },
        returnPosition: function(position) {

            this.geoEnabled = true;

            this.userCoords.latitude  = position.coords.latitude;
            this.userCoords.longitude = position.coords.longitude;

        },
        showServicesWithinDistance: function() {
                this.$emit('filterByDistance', this.userCoords, this.selectedDistance);
        }
    }
}
</script>
