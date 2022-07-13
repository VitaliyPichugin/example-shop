<template>
    <node-tree :node="treeData"
               :event="'click'"
               :parent="'#sidebarNavG-' + treeData.id"
               :ulid="'sidebarNavG-' + treeData.id"
               :aria="'sidebarNav1CollapseG-' + treeData.id"
               :target="'#sidebarNav1CollapseG-' + treeData.id">
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

<style scoped>
</style>
