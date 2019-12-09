<template>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{service.title}}</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ service.description }}e</h6>
            <a href="#" @click.prevent="removeService" class="card-link">Delete</a>            
        </div>
    </div>
</template>

<script>
export default {
    props: ['service'],
    data: function() {
        return {
            id: this.service.id
        }
    },
    methods: {
        removeService: function() {
            axios.delete('services/' + this.id)
            .then((response) => {
                console.log(response);

                this.$emit('removeService', response.data)
            })
        }
    }
}
</script>