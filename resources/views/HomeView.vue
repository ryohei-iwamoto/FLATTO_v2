<template>
    <v-container fluid>
        <h4 class="mb-5">ToDo一覧</h4>
        <div class="table-list">
            <v-table density="compact">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>タイトル</th>
                        <th>ステータス</th>
                        <th>作成日</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in todoList" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.TODO_NAME }}</td>
                        <td>{{ item.STATUS }}</td>
                        <td>{{ item.CREATE_DATE }}</td>
                    </tr>
                </tbody>
            </v-table>
        </div>
    </v-container>
</template>

<script setup>
import { ref } from 'vue'

// 一覧取得
const todoList = ref([
    { TODO_NAME: "タスク1", STATUS: "完了", CREATE_DATE: "2023-06-17" },
    { TODO_NAME: "タスク2", STATUS: "未完了", CREATE_DATE: "2023-06-16" },
    { TODO_NAME: "タスク3", STATUS: "完了", CREATE_DATE: "2023-06-15" },
    { TODO_NAME: "タスク4", STATUS: "未完了", CREATE_DATE: "2023-06-14" }
])

</script>
