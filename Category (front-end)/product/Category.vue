<template>
    <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar">
        <li class="all-tree">
            <a class="dropdown-toggle dropdown-toggle-collapse dropdown-title" href="javascript:;" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="sidebarNav1Collapse" data-target="#sidebarNav1Collapse">
                Show All Categories
            </a>
            <div id="sidebarNav1Collapse" class="collapse" data-parent="#sidebarNav">
                <ul id="sidebarNav-0" class="list-unstyled mb-0 sidebar-navbar">
                    <tree v-for="(item, index) in DATA_CATEGORY" :current="$route.params.cat" :key="index" :tree-data="item"></tree>
                </ul>
            </div>
        </li>
        <li class="current-tree">
            <a class="dropdown-current" :style="{'color': !CURRENT_CATEGORY.children ? '#66afe9' : ''}" style="font-size: 16px" href="#">{{ CURRENT_CATEGORY.name }}</a>
            <ul class="list-unstyled dropdown-list">
                <tree v-for="(item, index) in CURRENT_CATEGORY.children" :current="$route.params.cat" :key="index" :tree-data="item"></tree>
            </ul>
        </li>
    </ul>
</template>

<script>
import Tree from "./Tree";
import {mapActions, mapGetters, mapMutations} from "vuex";
export default {
    name: "Category",
    components: {
        tree: Tree
    },
    data: function () {
        return {
            currentTree: {},
        }
    },
    methods: {
        ...mapActions([
            'GET_CURRENT_CATEGORY',
        ]),
        getCurrentTree(){
            let self = this;
            let current = parseInt(self.$route.params.cat);
            //setTimeout(function (){
                self.DATA_CATEGORY.map(function (item){
                    if(current === item.category_id){
                        self.currentTree = item;
                    }
                })

           // }, 500)
        },
    },
    computed: {
        ...mapGetters([
            'DATA_CATEGORY',
            'CURRENT_CATEGORY',
        ]),
    },
    mounted() {
        this.GET_CURRENT_CATEGORY(this.$route.params.cat);
    },
}
</script>

<style scoped>

</style>
