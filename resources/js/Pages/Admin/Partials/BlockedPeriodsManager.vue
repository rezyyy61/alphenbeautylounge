<template>
    <div>
        <Blocks
            :refreshKey="refreshKey"
            @create="handleCreate"
            @edit="handleEdit"
        />

        <div v-if="showForm" class="mt-8">
            <BlockTimeForm
                :initial="editingBlock"
                @saved="handleSaved"
                @cancel="handleCancel"
            />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import BlockTimeForm from "@/Pages/Admin/Partials/BlockTimeForm.vue"
import Blocks from "@/Pages/Admin/Partials/Blocks.vue"

const showForm = ref(false)
const editingBlock = ref(null)
const refreshKey = ref(0)

function handleCreate() {
    editingBlock.value = null
    showForm.value = true
}

function handleEdit(block) {
    editingBlock.value = block
    showForm.value = true
}

function handleSaved() {
    showForm.value = false
    editingBlock.value = null
    refreshKey.value++
}

function handleCancel() {
    showForm.value = false
    editingBlock.value = null
}
</script>
