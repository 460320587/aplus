<style lang="scss" rel="stylesheet/scss">
    .avatar-uploader {
        .el-upload {
            border: 1px dashed #d9d9d9;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background-color: #fbfdff;
            .avatar-uploader-icon {
                line-height: 148px;
            }
            &.el-upload--text {
                width: 148px;
                height: 148px;
                position: relative;
                .avatar {
                    position: absolute;
                    width: 100%;
                    border-radius: 0;
                    top: 0;
                    bottom: 0;
                    margin: auto;
                }
            }
        }
        &:hover {
            .el-upload {
                border-color: #20a0ff;
                color: #20a0ff;
            }
        }
    }
    .el-upload__input {
        display: none!important;
    }
</style>

<template>

    <div>

        <div class="row">

            <div class="col-md-6">
                <button class="btn btn-default" @click="handleNodeAdd">添加栏目</button>
                <el-dialog :title="addFormTitle" v-model="isDialogAddVisible" :visible.sync="isDialogAddVisible">

                    <form class="form-horizontal" @submit.prevent="onSubmitAddForm">

                        <someline-form-group-input
                                placeholder="名称"
                                v-model="add_form.category_name"
                                :required="true"
                        >
                            <template slot="Label">名称</template>
                            <!--<pre class="m-t-sm m-b-none">{{ add_form.category_name }}</pre>-->
                        </someline-form-group-input>
                        <someline-form-group-line/>

                        <!--<someline-form-group-->
                        <!--:required="false">-->
                        <!--<template slot="Label">图片</template>-->
                        <!--<template slot="ControlArea">-->
                        <!--<el-upload-->
                        <!--class="avatar-uploader"-->
                        <!--:action="url('image')"-->
                        <!--name="image"-->
                        <!--:with-credentials="true"-->
                        <!--:show-file-list="false"-->
                        <!--:data="imageUploadData"-->
                        <!--:on-success="handleImageSuccessForAdd">-->
                        <!--<img v-if="add_form.someline_image_url" :src="add_form.someline_image_url"-->
                        <!--class="avatar">-->
                        <!--<i v-else class="el-icon-plus avatar-uploader-icon"></i>-->
                        <!--</el-upload>-->
                        <!--</template>-->
                        <!--&lt;!&ndash;<pre class="m-t-sm m-b-none">{{ add_form.someline_image_id }}</pre>&ndash;&gt;-->
                        <!--</someline-form-group>-->
                        <!--<someline-form-group-line/>-->

                        <someline-form-group>
                            <template slot="ControlArea">
                                <button type="submit" class="btn btn-primary" :disabled="isLoading">添加</button>
                            </template>
                            <!--<pre class="m-t-sm m-b-none">{{ add_form }}</pre>-->
                        </someline-form-group>

                    </form>

                </el-dialog>
            </div>

        </div>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <someline-tree-view
                        :tree-data="treeData"
                        :max-depth="2"
                        :editable="true"
                        :handle-node-action="handleNodeAction"
                        :on-node-active="onNodeActive"
                ></someline-tree-view>
            </div>
        </div>

        <el-dialog title="操作" v-model="isDialogActionVisible" :visible.sync="isDialogActionVisible">
            <a href="#" class="btn btn-default" @click.prevent="handleNodeAddChild" v-if="isAllowChildren"
               :disabled="!isAllowChildren"><i class="fa fa-plus"></i> &nbsp;添加子栏目</a>
            <a href="#" class="btn btn-default m-l text-danger"
               :disabled="isLoading"
               @click.prevent="handleNodeDelete"><i
                    class="fa fa-trash"></i> &nbsp;删除当前栏目及所有子栏目</a>
            <hr>
            <div class="h4 text-center m-t-lg m-b-lg">更新当前栏目信息</div>
            <form class="form-horizontal" @submit.prevent="onSubmitEditForm">

                <someline-form-group-input
                        placeholder="名称"
                        v-model="edit_form.category_name"
                        :required="true"
                >
                    <template slot="Label">名称</template>
                    <!--<pre class="m-t-sm m-b-none">{{ edit_form.category_name }}</pre>-->
                </someline-form-group-input>
                <someline-form-group-line/>

                <someline-form-group
                        :required="false">
                    <template slot="Label">图片</template>
                    <template slot="ControlArea">
                        <el-upload
                                class="avatar-uploader"
                                :action="url('image')"
                                name="image"
                                :with-credentials="true"
                                :show-file-list="false"
                                :data="imageUploadData"
                                :on-success="handleImageSuccessForEdit">
                            <img v-if="edit_form.someline_image_url" :src="edit_form.someline_image_url" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </template>
                    <!--<pre class="m-t-sm m-b-none">{{ edit_form.someline_image_id }}</pre>-->
                </someline-form-group>
                <someline-form-group-line/>

                <someline-form-group>
                    <template slot="ControlArea">
                        <button type="submit" class="btn btn-primary" :disabled="isLoading">保存</button>
                    </template>
                    <!--<pre class="m-t-sm m-b-none">{{ edit_form }}</pre>-->
                </someline-form-group>

            </form>
        </el-dialog>

    </div>

</template>

<script>
    export default{
        props: ['category_type'],
        data(){
            return {

//                category_type: 'Product',
                add_form: {},
                edit_form: {},
                activeNode: null,
                activeNodeParent: null,
                isAllowChildren: false,

                isLoading: false,
                isDialogAddVisible: false,
                isDialogActionVisible: false,
                isDialogSelectVisible: false,
                formLabelWidth: '120px',
                imageUrl: '',

                treeData: [],

            }
        },
        computed: {
            addFormTitle(){
                if (this.add_form.parent_category_id) {
                    return '添加子栏目';
                } else {
                    return '添加栏目';
                }
            },
            imageUploadData() {
                return {
                    _token: this.csrfToken
                }
            },
        },
        components: {
        },
        watch: {},
        events: {},
        mounted(){
            this.getCategoryList()
        },
        methods: {
            getCategoryList () {
                this.$api.get('categories', {
                    params: {
                        type: this.category_type,
                        all_children: 1
                    }
                })
                    .then(response => {
                        this.treeData = response.data.data
                    }, error => this.$message.error(error.data.message))
            },
            onNodeActive(node, parent){
                console.log('onNodeActive', node, parent);
                this.activeNode = node;
                this.activeNodeParent = parent;
            },
            handleNodeAdd(parent_node, depth){
                this.doResetFormDataForAddNode(parent_node)
                this.isDialogAddVisible = true;
            },
            handleNodeAddChild(){
                this.handleNodeAdd(this.activeNode)
            },
            handleNodeAction(node, isAllowChildren, depth){
                console.log('handleNodeAction');
                this.isAllowChildren = isAllowChildren;
                this.doResetFormDataForEditNode(node);
                this.isDialogActionVisible = true;
            },
            handleNodeDelete(node){
                console.log('handleNodeDelete');
                if (!confirm('确认删除栏目？')) {
                    return;
                }

                this.onSubmitDeleteForm();
            },
            handleImageSuccessForAdd(res, file){
                console.log('handleImageSuccessForAdd', res);
                this.add_form.someline_image_id = res.data.someline_image_id;
                this.add_form.someline_image_url = res.data.someline_image_url;
            },
            handleImageSuccessForEdit(res, file){
                console.log('handleImageSuccessForEdit', res);
                this.edit_form.someline_image_id = res.data.someline_image_id;
                this.edit_form.someline_image_url = res.data.someline_image_url;
            },
            doResetFormDataForAddNode(node){
                console.log('doResetFormDataForAddNode');
                this.add_form = {
                    parent_category_id: node ? node.someline_category_id : null,
                    type: this.category_type,
                    category_name: null,
                    someline_image_id: null,
                    someline_image_url: null,
                };
            },
            doResetFormDataForEditNode(node){
                console.log('doResetFormDataForEditNode');
                this.edit_form = {
                    someline_category_id: node.someline_category_id,
                    category_name: node.category_name,
                    someline_image_id: node.someline_image_id,
                    someline_image_url: node.someline_image_url,
                };
            },
            onSubmitAddForm(){
                console.log('onSubmitAddForm');

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                this.$api.post('categories', this.add_form)
                    .then(response => {
                        this.$message.success('添加成功')
                        console.log(response)
                        this.isDialogAddVisible = false;

                        var newCategory = response.data.data
                        if (this.add_form.parent_category_id) {
                            if (!this.activeNode.children) {
                                Vue.set(this.activeNode, 'children', [])
                            }
                            this.activeNode.children.push(newCategory)
                        } else {
                            this.treeData.push(newCategory)
                        }

                    }, error => this.$message.error(error.data.message))
                    .finally(() => {
                        this.isLoading = false;
                    })
            },
            onSubmitEditForm(){
                console.log('onSubmitEditForm');

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                this.$api.put('categories/' + this.edit_form.someline_category_id, this.edit_form)
                    .then(response => {
                        this.$message.success('更新成功')
                        console.log(response)
                        this.activeNode = Object.assign(this.activeNode, response.data.data)
                        this.isDialogActionVisible = false;
                    }, error => this.$message.error(error.data.message))
                    .finally(() => {
                        this.isLoading = false;
                    })
            },
            onSubmitDeleteForm(){
                console.log('onSubmitDeleteForm');

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                let somelineCategoryId = this.edit_form.someline_category_id;
                this.$api.delete('categories/' + somelineCategoryId)
                    .then(response => {
                        this.$message.success('删除成功')
                        console.log(response)

                        let activeNodeParent = this.activeNodeParent;
                        _.remove(activeNodeParent, function (currentObject) {
                            return currentObject.someline_category_id === somelineCategoryId;
                        });
                        Vue.set(this.activeNodeParent, activeNodeParent)

                        this.isDialogActionVisible = false;
                    }, error => this.$message.error(error.data.message))
                    .finally(() => {
                        this.isLoading = false;
                    })
            },
        }
    }
</script>