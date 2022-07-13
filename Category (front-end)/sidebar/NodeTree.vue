<template>
    <li v-if="node.children && node.children.length" class="u-has-submenu u-header-collapse__submenu">
        <a class="u-header-collapse__nav-link u-header-collapse__nav-pointer"
           href="javascript:;"
           role="button"
           data-toggle="collapse"
           :aria-expanded="false"
           :aria-controls="'parent-'+node.id"
           :data-target="'#parent-'+node.id">
            <a v-on:click.stop.prevent="goTo(node)" class="icon-external"><i class="fas fa-external-link-alt"></i></a>{{ node.name }}
        </a>
        <div :id="'parent-'+node.id"
             class="collapse"
             :data-parent="'#'+parent">
            <ul :id="'menu-'+node.id" class="u-header-collapse__nav-list">
                <node v-for="(child, index) in node.children"
                      :node="child"
                      v-bind:key="index"
                      :parent="'parent-'+node.id">
                </node>
            </ul>
        </div>
    </li>
    <li v-else>
        <a class="u-header-collapse__submenu-nav-link font-weight-bolder" :href="getLink(node)">
            {{ node.name }}
        </a>
    </li>
</template>

<script>
import toSlug from "../../../filters/toSlug";

export default {
    name: "node",
    props: {
        node: null,
        parent: null,
    },
    methods: {
        goTo(node){
            let param = toSlug(node.name) + '_' + node.category_id;
            this.$router.push({ name: 'products', params: { cat: param } }).catch(err => {});
            window.location.reload();//temp lol
        },
        getLink(node){
            let param = 'catalog/'+toSlug(node.name) + '_' + node.category_id;
            return `/${document.documentElement.lang}/${param}`;
        }
    },
}
</script>

<style scoped>
.icon-external {
    display: inline-block;
    margin-right: .8rem;
    height: 1.2rem;
    width: 1.2rem;
    color: #333e48;
}
.icon-external:hover{
    color: #66afe9;
}
</style>
