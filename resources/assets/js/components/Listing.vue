<template>
    <div class="container">
        <div class="row">
            <div class="list-group">
                <li class="list-group-item" v-for="name in names"><router-link :to="{ name: 'vad', params: { id: name.name }}">{{ name.name }}</router-link></li>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        computed: {
            ...mapGetters(['names'])
        },
        methods: {
            ...mapActions([
                'loadNames'
            ])
        },
        mounted() {
            this.loadNames()
        }
    }
</script>