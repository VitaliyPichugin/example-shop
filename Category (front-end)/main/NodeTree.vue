<template>
    <li v-if="node.children && node.children.length"
        class="nav-item hs-has-sub-menu u-header__nav-item"
        :data-event='event'
        data-animation-in="slideInUp"
        data-animation-out="fadeOut"
        data-position='left'>
        <a :id="node.id"
           class="nav-link u-header__nav-link  u-header__nav-link-toggle"
           href="javascript:;"
           aria-haspopup='true'
           aria-expanded='false'
           :aria-labelledby="node.id">
            <span v-on:click.stop.prevent="goTo(node)" class="icon-external"><i class="fas fa-external-link-alt"></i></span>{{node.name}}
        </a>
        <ul :id="ulid"
            class="hs-sub-menu u-header__sub-menu animated hs-position-left last-wrap"
            :aria-labelledby="node.id"
            style="display: none;">
            <span class="u-header__sub-menu-title" style="font-size: 16px; color: #333e48;">{{node.name}}</span>
            <node v-for="(child, index) in node.children"
                  :node="child"
                  :event="'hover'"
                  v-bind:key="index"
                  :ulid="'sidebarNav-' + child.category_id"
                  :parent="'#sidebarNav-' + child.category_id"
                  :aria="'sidebarNav1Collapse-' + child.category_id"
                  :target="'#sidebarNav1Collapse-' + child.category_id">
            </node>
        </ul>
    </li>
    <li v-else class="nav-item u-header__nav-item">
        <a class="nav-link u-header__nav-link"
           data-event="click"
           data-position="click"
           style="font-weight: 700; color: #333e48;"
           :href="getLink(node)">
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
        aria: null,
        ulid: null,
        target: null,
        event: '',
    },
    methods: {
        goTo(node){
            let param = toSlug(node.name) + '_' + node.category_id;
            this.$router.push({ name: 'products', params: { cat: param } }).catch(err => {});
            window.location.reload();//temp
        },
        getLink(node){
            let param = 'catalog/'+toSlug(node.name) + '_' + node.category_id;
            return `/${document.documentElement.lang}/${param}`;
        },
    }
}
</script>

<style scoped>

.icon-external {
    display: inline-block;
    margin-right: .8rem;
    height: 1.2rem;
    width: 1.2rem;
}
.icon-external:hover{
    color: #66afe9;
}

</style>
