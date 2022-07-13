<template>
    <div class="category-group col-md-4 p-0" style="vertical-align: top">
        <h6 width="100%" class="m-0 p-0 text-center gen-category" style="display: inline" v-on:click.stop.prevent="goTo(data)"><strong>{{data['name_' + __lang()]}}</strong></h6>
        <div style="clear: both"></div>
        <div class=" col-12  m-1 p-0" style="vertical-align: top" >
            <div class="col-md-5 m-0 p-0 category-img"
                 style="float: left"
                 v-on:click.stop.prevent="goTo(data)">
                <img  width="100%" src="/icon/no-image.png" class="" alt="...">
            </div>
            <div class="col-md-7 m-0 p-0"  style="float: right">
                <ul class="list-group m-0 pr-1 cat-name">
                    <li v-for="(item, index) in data.child" v-if="item['name_' + __lang()]"
                        :key="index"
                        v-on:click.stop.prevent="goTo(item)"
                        class="list-group-item p-0 pr-0 category-name">
                        <span class="ml-1">{{`${item['name_' + __lang()]}`}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import toSlug from "../../filters/toSlug";

export default {
    props: ['data'],
    name: "Category",
    methods:{
        goTo(node){
            let param = toSlug(node['name_' + this.__lang()]) + '_' + node.category_id;
            this.$router.push({ name: 'products', params: { cat: param } }).catch(err => {});
        },
    }
}
</script>

<style scoped>
.cat-name{
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
}
.gen-category:hover, .category-name:hover, .category-img:hover{
    color: #0b7ec4;
}
.gen-category, .category-name, .category-img{
    cursor: pointer;
}
</style>
