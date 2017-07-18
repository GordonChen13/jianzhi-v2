<template>
    <div class="container">
        <Navbar></Navbar>
        <p>{{user}} {{token}}</p>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'Home',
        components:{Navbar},
        data() {
            return {
                user: null,
                token: null
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {

            }

    }
</script>