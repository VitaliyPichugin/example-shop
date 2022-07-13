<template>
    <li v-if="node.children && node.children.length" class="node-tree">
        <span v-if="active(node.category_id)">
             <a class="dropdown-toggle dropdown-toggle-collapse dropdown-title custom-child selected-item"
                href="javascript:;"
                role="button"
                data-toggle="collapse"
                :aria-expanded="true"
                :aria-controls="aria"
                :data-target="target">
            <a v-on:click.stop.prevent="goTo(node)" class="icon-external"><i class="fas fa-external-link-alt"></i></a>{{ node.name }}</a>
            <div :id="aria"  class="collapse show" :data-parent="parent">
                <ul :id="ulid" class="list-unstyled dropdown-list">
                    <node v-for="(child, index) in node.children"
                          :node="child"
                          v-bind:key="index"
                          :ulid="'sidebarNav-' + child.category_id"
                          :parent="'#sidebarNav-' + child.category_id"
                          :aria="'sidebarNav1Collapse-' + child.category_id"
                          :target="'#sidebarNav1Collapse-' + child.category_id">
                    </node>
                </ul>
            </div>
        </span>
        <span v-else>
             <a class="dropdown-toggle dropdown-toggle-collapse dropdown-title custom-child"
                href="javascript:;"
                role="button"
                data-toggle="collapse"
                :aria-expanded="false"
                :aria-controls="aria"
                :data-target="target">
            <a v-on:click.stop.prevent="goTo(node)" class="icon-external"><i class="fas fa-external-link-alt"></i></a>{{ node.name }}</a>
            <div :id="aria" class="collapse" :data-parent="parent">
                <ul :id="ulid" class="list-unstyled dropdown-list">
                    <node v-for="(child, index) in node.children"
                          :node="child"
                          v-bind:key="index"
                          :ulid="'sidebarNav-' + child.category_id"
                          :parent="'#sidebarNav-' + child.category_id"
                          :aria="'sidebarNav1Collapse-' + child.category_id"
                          :target="'#sidebarNav1Collapse-' + child.category_id">
                    </node>
                </ul>
            </div>
        </span>
    </li>
    <li v-else>
        <a v-if="parseInt(current) === node.category_id"  class="dropdown-item custom-hover current-item">{{ node.name }}
            <span class="text-gray-25 font-size-12 font-weight-normal "></span>
        </a>
        <a v-else  class="dropdown-item custom-hover"
           :class="{'selected-item': active(node.category_id)}"
           href="javascript:;"
           @click="goTo(node)">{{ node.name }}
            <span class="text-gray-25 font-size-12 font-weight-normal "></span>
        </a>
    </li>
</template>

<script>
import {mapGetters} from "vuex";
import toSlug from "../../../filters/toSlug";

export default {
    name: "node",
    lang: document.documentElement.lang,
    filters: {
        toSlug,
    },
    props: {
        node: null,
        parent: null,
        aria: null,
        ulid: null,
        target: null,
        current: null,
    },
    methods: {
        goTo(node){
            let param = toSlug(node.name) + '_' + node.category_id;
            this.$router.push({ name: 'products', params: { cat: param } }).catch(err => {});
        },
        active(cat_id){
            return this.SELECTED_CATEGORY.includes(cat_id)
        }
    },
    computed: {
        ...mapGetters([
            'SELECTED_CATEGORY',
        ]),
    },
}
</script>

<style scoped>

.dropdown-item, .dropdown-toggle{
    margin: 0 10px 0 5px !important;
    padding: 0 !important;
}
.current-item{
    color: #66afe9!important;
    font-size: 16px;
}
.selected-item{
    color: #66afe9!important;
}
.custom-hover:hover{
    color: #66afe9!important;
}
.custom-hover, .custom-child{
    white-space: normal;
    word-wrap: normal;
    padding-left: 14px
}
.icon-external {
    display: inline-block;
    margin-right: .4rem;
    height: 1.2rem;
    width: 1.2rem;
    color: #333e48;
}
.icon-external:hover{
    color: #66afe9;
}
</style>
