<template>
    <node-tree :node="treeData"
               :parent="'parent-0'">
    </node-tree>
</template>

<script>
import NodeTree from "./NodeTree";
export default {
    name: "Tree",
    props: {
        treeData: null,
    },
    components: {
        NodeTree
    }
}
</script>
