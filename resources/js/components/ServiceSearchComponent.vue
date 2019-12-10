<template>
    <div class="mb-4" >
        <h4>Services</h4>
        <div class="d-flex mb-2" >
            <input v-model="userSearchString" class="form-control" type="text" placeholder="search services">
            <button @click="sendSearch" class="ml-2 text-white btn btn-info" >Submit</button>
        </div>
        <div v-if="geoEnabled" >
            <select @change="showServicesWithinDistance" v-model="selectedDistance">
                <option disabled value="">Please select one</option>
                <option v-for="distance in distanceObj" :key="distance.distance" :value="distance.distance" >
                    {{ distance.title }}
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
            distanceObj: [
                {
                    title: '1',
                    distance: 1
                },
                {
                    title: '2',
                    distance: 2                    
                },
                {
                    title: '5',
                    distance: 5
                },
                {
                    title: '10',
                    distance: 10
                },
                {
                    title: '25',
                    distance: 25
                },
                {
                    title: '50',
                    distance: 50
                },
                {
                    title: '100',
                    distance: 100
                },
                {
                    title: 'Anywhere',
                    distance: 999999
                }
            ],
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
