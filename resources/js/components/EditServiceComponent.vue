<template>
    <p>
        {{ this.editableService }}
    </p>
</template>

<script>
export default {
    props: ['editableService'],
    data: function() {
        return {
            ser: this.editableService
        }
    }
}
</script>