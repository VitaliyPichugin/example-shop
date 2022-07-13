<template>
    <div class="filter-form" v-if="NESTED_CATEGORY.data && NESTED_CATEGORY.data.length">
        <div class="row">
            <h4>{{ NESTED_CATEGORY.data[0]['name_' + __lang()] }}</h4>
        </div>
        <div  class="row">
            <category v-for="item in NESTED_CATEGORY.data[0].child"
                           :findex="item.id"
                           :key="item.id"
                           :data="item">
            </category>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import Category from "./Category";
export default {
    name: "CategoryList",
    components: {Category},
    computed: {
        ...mapGetters([
            'IS_MOBILE',
            'IS_DESKTOP',
            'NESTED_CATEGORY',
        ]),
    },
    methods: {
        ...mapActions([
            'GET_NESTED_CATEGORY'
        ]),
    },
    mounted() {
        this.GET_NESTED_CATEGORY({id:this.$route.params.cat});
    }
}
</script>

<style scoped>

</style>
