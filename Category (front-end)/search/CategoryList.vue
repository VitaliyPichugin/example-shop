<template>
    <div  class="row">
        <category v-for="item in NESTED_CATEGORY.data"
                  :findex="item.id"
                  :key="item.id"
                  :data="item">
        </category>
    </div>
</template>

<script>
import Category from "./Category";
import {mapActions, mapGetters} from "vuex";
export default {
    name: "CategoryList",
    components: {Category},
    methods: {
        ...mapActions([
            'GET_CATEGORY_SEARCH'
        ]),
    },
    computed: {
        ...mapGetters([
            'NESTED_CATEGORY',
        ]),
    },
}
</script>
