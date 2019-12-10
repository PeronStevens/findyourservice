<template>
    <div>
        <p>
            Add New Service
        </p>
        <div class="mb-4" >
            <input ref="autocomplete" 
                   placeholder="Search Address" 
                   class="search-location"
                   onfocus="value = ''" 
                   type="text" 
                   v-model="googleAddress"
                   />
        </div>

        <form @submit.prevent="addService" >
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" v-model="formFields.title" aria-describedby="" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" v-model="formFields.description" id="description" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" :value="formFields.address" id="street_number" required readonly>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" :value="formFields.city" id="locality" required readonly>
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" class="form-control" :value="formFields.state" id="administrative_area_level_1" required readonly>
            </div>
            <div class="form-group">
                <label for="zipcode">Zipcode</label>
                <input type="text" class="form-control" :value="formFields.zipcode" id="postal_code" required readonly>
            </div>
            <div class="form-group d-none">
                <input type="text" class="form-control" v-model="formFields.latitude" id="latitude" required readonly>
            </div>
            <div class="form-group d-none">
                <input type="text" class="form-control" v-model="formFields.longitude" id="lomgitude" required readonly>
            </div>
            <div class="form-group d-none">
                <input type="text" class="form-control"  id="route" required readonly>
            </div>
            <div class="form-group d-none">
                <input type="text" class="form-control" id="country" required readonly>
            </div>                                    
            <button type="submit" class="btn btn-primary">Add</button>
        </form>     
    </div>

</template>

<script>
export default {
    data: function() {
        return  {
            googleAddress: "",
            formFields: {
                title       : "",
                description : "",
                address     : "",
                city        : "",
                state       : "",
                zipcode     : "",
                longitude   : "",
                latitude    : ""
            },
            componentForm: {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name',
                sublocality_level_1: 'long_name'
            }
        }
    },
    mounted() {
        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            {types: ['geocode']}
        );

        this.autocomplete.addListener('place_changed', () => {

            let place = this.autocomplete.getPlace();
            let ac = place.address_components;
            let lat = place.geometry.location.lat();
            let lon = place.geometry.location.lng();
            let city = ac[0]["short_name"];
            let addressStr = '';

            // for (var component in this.componentForm) {
            //     document.getElementById(component).value = '';
            //     document.getElementById(component).disabled = false;
            // }

            // Get each component of the address from the place details,
            // and then fill-in the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (this.componentForm[addressType]) {
                    var val = place.address_components[i][this.componentForm[addressType]];
                    
                    if (addressType == 'locality')                    
                        this.formFields.city = val;
                        if (this.formFields.city == "") 
                            if (addressType == 'sublocality_level_1')
                                this.formFields.city = val;

                    if (addressType == 'administrative_area_level_1') this.formFields.state = val;
                    if (addressType == 'postal_code')                 this.formFields.zipcode = val;
                    if (addressType == 'street_number')               addressStr = val;
                    if (addressType == 'route')                       addressStr += ' ' + val;

                    this.formFields.address = addressStr;

                }
            }

            this.formFields.latitude  = place.geometry.location.lat();
            this.formFields.longitude = place.geometry.location.lng();
        
        });
    },
    methods: {
        addService: function() {
            axios.post('services', this.formFields)
            .then((response) => {

                Object.keys(this.formFields).forEach(key => {
                    this.formFields[key] = ""
                });

                this.googleAddress = "";

                this.$emit('newService', response.data);

            })
        }
    }
}
</script>

<style scoped>

.search-location {
    display: block;
    width: 100%;
    margin: 0 auto;
    font-size: 20px;
    font-weight: 400;
    outline: none;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border-radius: 10px;
}
</style>

