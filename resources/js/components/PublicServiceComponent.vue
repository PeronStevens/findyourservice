<template>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{service.title}}</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ service.description }}</h6>
            <hr>            
            <h6 class="card-subtitle mb-2 text-muted">{{ service.address + ' ' + service.city + ', ' + service.state }}</h6>
        </div>
    </div>
</template>

<script>
export default {
    props: ['service']
}
</script>