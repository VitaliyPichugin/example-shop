<template>
    <div v-show="DATA_CATEGORY.length" id="basicsCollapseOne" :class="cls" class="collapse vertical-menu v1"
         aria-labelledby="basicsHeadingOne"
         data-parent="#basicsAccordion">
        <div class="card-body p-0">
            <nav class="js-mega-menu navbar navbar-expand-xl u-header__navbar u-header__navbar--no-space hs-menu-initialized">
                <div id="navBar" class="collapse navbar-collapse u-header__navbar-collapse">
                    <ul class="navbar-nav u-header__navbar-nav border-primary border-top-0">
                        <tree v-for="(item, index) in DATA_CATEGORY" :key="index" :tree-data="item"></tree>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
import Tree from "./Tree";
import {mapActions, mapGetters} from "vuex";
export default {
    name: "Category",
    props: ['cls'],
    components: {
        tree: Tree
    },
    methods: {
        ...mapActions([
            'GET_CATEGORY',
        ]),
    },
    computed: {
        ...mapGetters([
            'IS_MOBILE',
            'IS_DESKTOP',
            'DATA_CATEGORY'
        ]),
    },
    mounted() {
        this.GET_CATEGORY();
    },
}
</script>

<style scoped>

</style>
