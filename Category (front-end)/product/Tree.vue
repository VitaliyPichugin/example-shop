<template>
    <node-tree :node="treeData"
               :parent="'#sidebarNavG-' + treeData.id"
               :ulid="'sidebarNavG-' + treeData.id"
               :aria="'sidebarNav1CollapseG-' + treeData.id"
               :target="'#sidebarNav1CollapseG-' + treeData.id"
               :current="current">
    </node-tree>
</template>

<script>
import NodeTree from "./NodeTree";
export default {
    name: "Tree",
    props: {
        treeData: null,
        current: 0,
    },
    components: {
        NodeTree
    }
}
</script>

<style scoped>
</style>
