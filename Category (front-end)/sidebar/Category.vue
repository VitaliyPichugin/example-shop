<template>
    <div class="js-scrollbar u-sidebar__body" >
        <div id="parent-0" class="u-sidebar__content u-header-sidebar__content">
            <!-- Logo -->
            <a class="d-flex ml-0 navbar-brand u-header__navbar-brand u-header__navbar-brand-vertical" href="/" aria-label="Semicom">
<!--                <img src="https://semicom.lv/skins/wt/images/semicom-logo.png">-->
                Categories
            </a>
            <!-- End Logo -->
            <!-- List -->
            <ul id="headerSidebarList" class="u-header-collapse__nav">
                <tree v-for="(item, index) in DATA_CATEGORY" :key="index" :tree-data="item"></tree>
            </ul>
            <!-- End List -->
        </div>
    </div>
</template>

<script>
import Tree from "./Tree";
import {mapGetters} from "vuex";
export default {
    name: "Category",
    components: {
        tree: Tree
    },
    computed: {
        ...mapGetters([
            'IS_MOBILE',
            'IS_DESKTOP',
            'DATA_CATEGORY'
        ]),
    },
}
</script>

<style scoped>

</style>
