<template>
    <div class="alert alert-dismissible fade show" :class="typeAlert" role="alert">
        {{ title }}
        <hr>

        <p>{{ details.message }}</p>
        <ul v-if="details.dados">
            <li v-for="e, key in details.dados" :key="key">{{ e[0] }}</li>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>
<script>
export default {
    name: 'AlertComponent',
    props: ['type', 'details', 'title'],
    computed: {
        typeAlert() {
            return "alert-" + this.type
        }
    }
}
</script>
