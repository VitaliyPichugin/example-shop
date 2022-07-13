<template>
    <div v-if="typeof data[0] !== 'undefined'" class="category-group col-md-4 p-0" style="vertical-align: top">
        <h6  class="m-0 p-0 text-center gen-category" style="display: inline" v-on:click.stop.prevent="goTo(data[0], $route.query.text)"><strong>{{data[0].parent }}</strong></h6>
        <div class=" col-12  m-1 p-0" style="vertical-align: top" >
            <div class="col-md-5 m-0 p-0 category-img"  style="float: left" v-on:click.stop.prevent="goTo(data[0], $route.query.text)"><img  width="100%" src="/icon/no-image.png" class="" alt="..."></div>
            <div class="col-md-7 m-0 p-0"  style="float: right">
                <ul class="list-group m-0 pr-1 cat-name">
                    <li v-for="(item, index) in data" v-if="item['category_name']" :key="index" v-on:click.stop.prevent="goTo(item, $route.query.text)" class="list-group-item p-0 pr-0 category-name">
                        <span class="ml-1">{{`${item['category_name']}`}} <strong>{{`(${item['cnt']})`}}</strong></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import toSlug from "../../../filters/toSlug";

export default {
    props: ['data'],
    name: "Category",
    methods:{
        goTo(node, text){
            let param = toSlug(node.category_name) + '_' + node.category_id;
            this.$router.push({ name: 'search', query: { text: text, category: param,  } }).catch(err => {});
        },
    },
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
