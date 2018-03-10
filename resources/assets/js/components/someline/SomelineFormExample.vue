<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            Someline 表单
        </template>

        <!--region ================== Control ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">组件</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group-static>
            <template slot="Label">静态组件</template>
            example@someline.com
        </someline-form-group-static>
        <someline-form-group-line/>

        <someline-form-group-static :lg="true">
            <template slot="Label">静态组件</template>
            <template slot="HelpText">添加 <code>lg</code> 属性可将字体变大</template>
            项目名称
        </someline-form-group-static>
        <someline-form-group-line/>

        <!--endregion / Control -->

        <!--region ================== Input ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">输入框</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="输入提示"
                v-model="form_data.example_input_text"
                :required="true"
        >
            <template slot="Label">输入框</template>
            <template slot="HelpText">这是一些帮助文字</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="邮箱"
                v-model="form_data.example_input_text1"
                type="email"
                :required="true"
        >
            <template slot="Label">邮箱</template>
            <template slot="HelpText">这是一些帮助文字</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text1 }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="密码"
                v-model="form_data.example_input_text2"
                type="password"
                :required="true"
        >
            <template slot="Label">密码</template>
            <template slot="HelpText">这是一些帮助文字</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text2 }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="数字"
                v-model="form_data.example_input_text3"
                type="number"
                min="1"
                max="100"
                :required="true"
        >
            <template slot="Label">数字</template>
            <template slot="HelpText">这是一些帮助文字</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text3 }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                name="example_input_text"
                :rounded="true"
                size="lg"
                input-class="bg-light lter"
                placeholder="输入提示"
                v-model="form_data.example_input_text"
        >
            <template slot="Label">输入框 (圆形)</template>
            <template slot="HelpText">圆形，<code>lg</code>外形，背景<code>.bg-light.dker</code></template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="禁用状态"
                :disabled="true"
                v-model="form_data.example_input_text"
        >
            <template slot="Label">输入框 (禁用)</template>
            <template slot="HelpText">这是一些帮助文字</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                name="example_input_text"
                has="error"
                placeholder="输入提示"
                v-model="form_data.example_input_text"
        >
            <template slot="Label">输入框 (错误状态)</template>
            <template slot="HelpText">可展示状态，状态 <code>error, success, warning</code></template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text }}</pre>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-full class="text-center">
            <div class="h3">自定义组</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">自定义表单组</template>
            <template slot="HelpText">支持自定义表单组</template>
            <template slot="ControlArea">
                <el-date-picker
                        v-model="form_data.example_custom_time"
                        type="datetime"
                        placeholder="选择日期时间">
                </el-date-picker>
            </template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_custom_time }}</pre>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">自定输入框</template>
            <template slot="HelpText">支持自定义</template>
            <template slot="ControlArea">
                <div class="row">
                    <div class="col-md-2">
                        <someline-form-control-input
                                name="example_input_text"
                                placeholder=".col-md-2"
                                v-model="form_data.example_input_text"
                        />
                    </div>
                    <div class="col-md-3">
                        <someline-form-control-input
                                name="example_input_text"
                                placeholder=".col-md-3"
                                v-model="form_data.example_input_text"
                        />
                    </div>
                    <div class="col-md-4">
                        <someline-form-control-input
                                name="example_input_text"
                                placeholder=".col-md-4"
                                v-model="form_data.example_input_text"
                        />
                    </div>
                </div>
            </template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_input_text }}</pre>
        </someline-form-group>
        <someline-form-group-line/>

        <!--endregion-->

        <!--region ================== Check Box ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">勾选框</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <!-- Single -->
        <someline-form-group-check-box-list
                name="example_check_box"
                size="lg"
                :items="single_checkbox_items"
                v-model="form_data.example_check_box">
            <template slot="Label">勾选框</template>
            <template slot="HelpText">仅一个勾选框，外观<code>lg</code>大款</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_check_box }}</pre>
        </someline-form-group-check-box-list>
        <someline-form-group-line/>

        <!-- Normal -->
        <someline-form-group-check-box-list
                name="example_check_box2"
                :items="checkbox_items"
                v-model="form_data.example_check_box2">
            <template slot="Label">勾选框</template>
            <template slot="HelpText">多个勾选框</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_check_box2 }}</pre>
        </someline-form-group-check-box-list>
        <someline-form-group-line/>

        <!-- Inline -->
        <someline-form-group-check-box-list
                name="example_check_box3"
                :inline="true"
                :items="checkbox_items"
                v-model="form_data.example_check_box3">
            <template slot="Label">勾选框（单行）</template>
            <template slot="HelpText">多个勾选框</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_check_box3 }}</pre>
        </someline-form-group-check-box-list>
        <someline-form-group-line/>

        <!--endregion / Check Box -->

        <!--region ================== Radio ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">单选框</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <!-- Single -->
        <someline-form-group-radio-list
                name="example_radio"
                size="sm"
                :items="single_checkbox_items"
                v-model="form_data.example_radio">
            <template slot="Label">单选框</template>
            <template slot="HelpText">仅一个单选框，外观<code>sm</code>小款</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_radio }}</pre>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <!-- Normal -->
        <someline-form-group-radio-list
                name="example_radio2"
                :items="checkbox_items"
                v-model="form_data.example_radio2">
            <template slot="Label">单选框</template>
            <template slot="HelpText">多个单选框</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_radio2 }}</pre>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <!-- Inline -->
        <someline-form-group-radio-list
                name="example_radio3"
                :inline="true"
                :items="checkbox_items"
                v-model="form_data.example_radio3">
            <template slot="Label">单选框（单行）</template>
            <template slot="HelpText">多个单选框</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_radio3 }}</pre>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <!--endregion / Radio -->

        <!--region ================== Switch ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">开关按钮</div>
        </someline-form-group-full>
        <someline-form-group-line/>


        <!-- Normal -->
        <someline-form-group-switch-list
                name="example_switch"
                :items="single_checkbox_items"
                v-model="form_data.example_switch">
            <template slot="Label">开关按钮</template>
            <template slot="HelpText">仅一个开关按钮，不推荐在开关右侧添加文字</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_switch }}</pre>
        </someline-form-group-switch-list>
        <someline-form-group-line/>


        <!-- Color -->
        <someline-form-group-switch-list
                name="example_switch"
                size="md"
                bg="danger"
                :items="single_checkbox_items"
                v-model="form_data.example_switch">
            <template slot="Label">开关按钮</template>
            <template slot="HelpText">颜色 <code>danger, info, primary, success, warning</code></template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_switch }}</pre>
        </someline-form-group-switch-list>
        <someline-form-group-line/>

        <!--endregion / Switch -->

        <!--region ================== Select ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">下拉列表</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group-select
                :items="select_items"
                v-model="form_data.example_select"
        >
            <template slot="Label">下拉列表</template>
            <template slot="HelpText">可选取一个</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_select }}</pre>
        </someline-form-group-select>
        <someline-form-group-line/>

        <someline-form-group-select
                :items="custom_select_items"
                item-text="myKey"
                item-value="myValue"
                v-model="form_data.example_select2"
        >
            <template slot="Label">自定义下拉列表</template>
            <template slot="HelpText">可选取一个</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_select2 }}</pre>
        </someline-form-group-select>
        <someline-form-group-line/>

        <!--endregion / Select -->

        <!--region ================== Tag ================== -->

        <!-- <someline-form-group-full class="text-center">
             <div class="h3">标签</div>
         </someline-form-group-full>
         <someline-form-group-line/>

         <someline-form-group-tag-list
                 type="info"
                 :closable="true"
                 :addable="true"
                 :items="simple_tag_items"
                 @close="handleTagCloseSimple"
                 @add-tag="handleTagAddNewSimple"
         >
             <template slot="Label">简单标签</template>
             <template slot="HelpText">可移除和添加新标签</template>
             <pre class="m-t-sm m-b-none">{{ simple_tag_items }}</pre>
         </someline-form-group-tag-list>
         <someline-form-group-line/>

         <someline-form-group-tag-list
                 type="info"
                 :closable="true"
                 :addable="true"
                 :items="tag_items"
                 @close="handleTagClose"
                 @add-tag="handleTagAddNew"
         >
             <template slot="Label">标签</template>
             <template slot="HelpText">可移除和添加新标签</template>
             <pre class="m-t-sm m-b-none">{{ tag_items }}</pre>
         </someline-form-group-tag-list>
         <someline-form-group-line/>-->

        <!--endregion / Tag -->

        <!--region ================== Editor ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">文本框</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group-text-area
                height="200px"
                v-model="form_data.example_textarea"
        >
            <template slot="Label">文本框</template>
            <template slot="HelpText">这是一个文本框</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_textarea }}</pre>
        </someline-form-group-text-area>
        <someline-form-group-line/>

        <!--endregion / Editor -->

        <!--region ================== Upload ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">图片上传</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group-image-upload v-model="form_data.example_image"
                                          :limit-size="1000"
                                          :max-image="1"
        >
            <template slot="Label">单张图片上传</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_image }}</pre>
        </someline-form-group-image-upload>
        <someline-form-group-line/>

        <someline-form-group-image-upload v-model="form_data.example_images"
                                          :multiple="true"
                                          :limit-size="500"
                                          :max-image="2"
        >
            <template slot="Label">多张图片上传</template>
            <pre class="m-t-sm m-b-none">{{ form_data.example_images }}</pre>
        </someline-form-group-image-upload>
        <someline-form-group-line/>
        <!--endregion / Upload -->

        <!--region ================== Modal ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">模态框</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <div class="text-center">
            <button type="button" class="btn btn-primary" @click="openSomelineModal">打开Modal</button>
        </div>
        <someline-modal v-model="somelineModalVisible">
            这是一个模态框
        </someline-modal>
        <someline-form-group-line/>
        <!--endregion Modal -->

        <!--region ================== Editor ================== -->

        <someline-form-group-full class="text-center">
            <div class="h3">编辑器</div>
        </someline-form-group-full>
        <someline-form-group-line/>

        <someline-form-group-editor
                height="500px"
                map-ak="F300593dbf91cd7f8890e52370fa0006"
                :log="true"
                :exclude-menus="editorExcludeMenus"
                :wang-image-upload="true"
                v-model="form_data.example_editor"
                @editor-config="onEditorConfig"
                @editor-ready="onEditorReady"
                @editor-change="onEditorChange"
        >
            <template slot="Label">编辑器</template>
            <template slot="HelpText">这是一个富文本编辑器</template>
            <hr>
            <p class="text-muted">富文本的内容: </p>
            <pre class="m-t-sm m-b-none">{{ form_data.example_editor }}</pre>
            <div class="m-t-sm text-muted">纯文本的内容:</div>
            <pre class="m-t-sm m-b-none">{{ form_data.example_editor_text }}</pre>
            <div class="m-t-sm text-muted">格式化的纯文本内容:</div>
            <pre class="m-t-sm m-b-none">{{ form_data.example_editor_format_text }}</pre>
        </someline-form-group-editor>
        <someline-form-group-line/>

        <!--endregion / Editor -->

        <div class="hide">

            <!-- ================== 未开放 ================== -->

            <someline-form-group-full class="text-center">
                <div class="h3">未开放</div>
            </someline-form-group-full>
            <someline-form-group-line/>

            <div class="form-group">
                <label class="col-sm-2 control-label">Input groups</label>
                <div class="col-sm-10">
                    <div class="input-group m-b">
                        <span class="input-group-addon">@</span>
                        <input type="text" class="form-control" placeholder="Username">
                    </div>
                    <div class="input-group m-b">
                        <input type="text" class="form-control">
                        <span class="input-group-addon">.00</span>
                    </div>
                    <div class="input-group m-b">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control">
                        <span class="input-group-addon">.00</span>
                    </div>
                    <div class="input-group m-b">
              <span class="input-group-addon">
                <input type="checkbox">
              </span>
                        <input type="text" class="form-control">
                    </div>
                    <div class="input-group">
              <span class="input-group-addon">
                <input type="radio">
              </span>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Button addons</label>
                <div class="col-sm-10">
                    <div class="input-group m-b">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">Go!</button>
              </span>
                        <input type="text" class="form-control">
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                <button class="btn btn-default" type="button">Go!</button>
              </span>
                    </div>
                </div>
            </div>
            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">With dropdowns</label>
                <div class="col-sm-10">
                    <div class="input-group m-b">
                        <div class="input-group-btn dropdown" dropdown>
                            <button type="button" class="btn btn-default" dropdown-toggle>Action <span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href>Action</a></li>
                                <li><a href>Another action</a></li>
                                <li><a href>Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href>Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <input type="text" class="form-control">
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-btn dropdown" dropdown>
                            <button type="button" class="btn btn-default" dropdown-toggle>Action <span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href>Action</a></li>
                                <li><a href>Another action</a></li>
                                <li><a href>Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href>Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div>
                </div>
            </div>
            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Segmented</label>
                <div class="col-sm-10">
                    <div class="input-group m-b">
                        <div class="input-group-btn dropdown" dropdown>
                            <button type="button" class="btn btn-default" tabindex="-1">Action</button>
                            <button type="button" class="btn btn-default" dropdown-toggle><span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href>Action</a></li>
                                <li><a href>Another action</a></li>
                                <li><a href>Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href>Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <input type="text" class="form-control">
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-btn dropdown" dropdown>
                            <button type="button" class="btn btn-default" tabindex="-1">Action</button>
                            <button type="button" class="btn btn-default" dropdown-toggle><span
                                    class="caret"></span></button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href>Action</a></li>
                                <li><a href>Another action</a></li>
                                <li><a href>Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href>Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div>
                </div>
            </div>


            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Button radio</label>
                <div class="col-sm-10">
                    <div class="btn-group m-r" ng-controller="ButtonsDemoCtrl">
                        <label class="btn btn-default" ng-model="radioModel" btn-radio="'Left'"
                               uncheckable>Left</label>
                        <label class="btn btn-default" ng-model="radioModel" btn-radio="'Middle'"
                               uncheckable>Middle</label>
                        <label class="btn btn-default" ng-model="radioModel" btn-radio="'Right'"
                               uncheckable>Right</label>
                    </div>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Button checkbox</label>
                <div class="col-sm-10">
                    <div class="btn-group">
                        <label class="btn btn-default" ng-model="checkModel.left" btn-checkbox>Left</label>
                        <label class="btn btn-default" ng-model="checkModel.middle"
                               btn-checkbox>Middle</label>
                        <label class="btn btn-default" ng-model="checkModel.right"
                               btn-checkbox>Right</label>
                    </div>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Slider</label>
                <div class="col-sm-10">
                    <input id="slider" ui-jq="slider" ui-options="{
              min: 0,
              max: 20,
              step: 1,
              value: 5
            }" class="slider slider-horizontal form-control" type="text"> 5
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Vertical slider</label>
                <div class="col-sm-10">
                    <input ui-jq="slider" class="slider slider-vertical form-control" type="text" value=""
                           data-slider-min="5" data-slider-max="20" data-slider-step="1"
                           data-slider-value="11" data-slider-orientation="vertical">
                    <input ui-jq="slider" class="slider slider-vertical form-control" type="text" value=""
                           data-slider-min="5" data-slider-max="20" data-slider-step="1"
                           data-slider-value="15" data-slider-orientation="vertical">
                    <input ui-jq="slider" class="slider slider-vertical form-control" type="text" value=""
                           data-slider-min="5" data-slider-max="20" data-slider-step="1"
                           data-slider-value="12" data-slider-orientation="vertical">
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Range selector</label>
                <div class="col-sm-10">
                    <input type="text" ui-jq="slider" class="slider form-control" value=""
                           data-slider-min="10" data-slider-max="1000" data-slider-step="5"
                           data-slider-value="[250,750]">
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Spinner</label>
                <div class="col-sm-10">
                    <div class="m-b">
                        <input ui-jq="TouchSpin" type="text" value="0" class="form-control" data-min='0'
                               data-max="10" data-step="0.1" data-decimals="2" data-prefix="$">
                    </div>
                    <div class="m-b">
                        <input ui-jq="TouchSpin" type="text" value="5" class="form-control" data-min='0'
                               data-max="10" data-step="0.1" data-decimals="2" data-postfix="%">
                    </div>
                    <div>
                        <input ui-jq="TouchSpin" type="text" value="10" class="form-control" data-min='0'
                               data-max="20" data-verticalbuttons="true"
                               data-verticalupclass="fa fa-caret-up"
                               data-verticaldownclass="fa fa-caret-down">
                    </div>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Chosen</label>
                <div class="col-sm-10">
                    <select ui-jq="chosen" class="w-full">
                        <optgroup label="Alaskan/Hawaiian Time Zone">
                            <option value="AK">Alaska</option>
                            <option value="HI">Hawaii</option>
                        </optgroup>
                        <optgroup label="Pacific Time Zone">
                            <option value="CA">California</option>
                            <option value="NV">Nevada</option>
                            <option value="OR">Oregon</option>
                            <option value="WA">Washington</option>
                        </optgroup>
                        <optgroup label="Mountain Time Zone">
                            <option value="AZ">Arizona</option>
                            <option value="CO">Colorado</option>
                            <option value="ID">Idaho</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NM">New Mexico</option>
                            <option value="ND">North Dakota</option>
                            <option value="UT">Utah</option>
                            <option value="WY">Wyoming</option>
                        </optgroup>
                        <optgroup label="Central Time Zone">
                            <option value="AL">Alabama</option>
                            <option value="AR">Arkansas</option>
                            <option value="IL">Illinois</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="OK">Oklahoma</option>
                            <option value="SD">South Dakota</option>
                            <option value="TX">Texas</option>
                            <option value="TN">Tennessee</option>
                            <option value="WI">Wisconsin</option>
                        </optgroup>
                        <optgroup label="Eastern Time Zone">
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="IN">Indiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="OH">Ohio</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WV">West Virginia</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Chosen multiple</label>
                <div class="col-sm-10">
                    <select ui-jq="chosen" multiple class="w-md">
                        <option value="AK">Alaska</option>
                        <option value="HI">Hawaii</option>
                        <option value="CA">California</option>
                        <option value="NV">Nevada</option>
                        <option value="OR">Oregon</option>
                        <option value="WA">Washington</option>
                    </select>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Tags input</label>
                <div class="col-sm-10">
                    <input ui-jq="tagsinput" ui-options="" class="form-control w-md"/>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Datepicker</label>
                <div class="col-sm-10" ng-controller="DatepickerDemoCtrl">
                    <div class="input-group w-md">
                        <input type="text" class="form-control" datepicker-popup="" ng-model="dt"
                               is-open="opened" datepicker-options="dateOptions" ng-required="true"
                               close-text="Close"/>
                        <span class="input-group-btn">
                <button type="button" class="btn btn-default" ng-click="open($event)"><i
                        class="glyphicon glyphicon-calendar"></i></button>
              </span>
                    </div>
                </div>
            </div>

            <someline-form-group-line/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Date range picker</label>
                <div class="col-sm-10">
                    <input ui-jq="daterangepicker" ui-options="{
                format: 'YYYY-MM-DD',
                startDate: '2013-01-01',
                endDate: '2013-12-31'
              }" class="form-control w-md"/>
                </div>
            </div>

            <someline-form-group-line/>

            <div class="form-group">
                <label class="col-sm-2 control-label">File input</label>
                <div class="col-sm-10">
                    <input ui-jq="filestyle" type="file" data-icon="false"
                           data-classButton="btn btn-default"
                           data-classInput="form-control inline v-middle input-s">
                </div>
            </div>
            <someline-form-group-line/>

        </div>

        <someline-form-group>
            <template slot="ControlArea">
                <button type="submit" class="btn btn-primary">保存</button>
            </template>
            <pre class="m-t-sm m-b-none">{{ form_data }}</pre>
        </someline-form-group>

    </someline-form-panel>

</template>

<script>
    export default {
        props: [],
        data () {
            return {

                isLoading: false,
                somelineModalVisible: false,
                single_checkbox_items: [
                    {
                        text: '我同意 Someline 服务条款和隐私条款',
                        value: 'yes',
                    }
                ],

                checkbox_items: [
                    {
                        text: 'A',
                        checked: true,
                    },
                    {
                        text: 'B',
                        value: 'custom_value',
                    },
                    {
                        text: 'C',
                    },
                    {
                        text: 'D (Disabled)',
                        disabled: true,
                    },
                ],

                select_items: [
                    {
                        text: 'Option A',
                    },
                    {
                        text: 'Option B',
                        value: 'custom_b',
                    },
                    {
                        text: 'Option C',
                    },
                ],

                custom_select_items: [
                    {
                        myKey: 'Option A',
                    },
                    {
                        myKey: 'Option B',
                        myValue: 'custom_b',
                    },
                    {
                        myKey: 'Option C',
                    },
                ],

                tag_items: [
                    {
                        tag_id: 1,
                        tag: 'Tag A',
                    },
                    {
                        tag_id: 2,
                        tag: 'Tag B',
                    },
                    {
                        tag_id: 3,
                        tag: 'Tag C',
                    },
                ],

                simple_tag_items: [
                    'Tag A',
                    'Tag B',
                    'Tag C',
                ],

                editor: null,
                editorExcludeMenus: [
                    'video',
                ],

                form_data: {
                    example_input_text: null,
                    example_input_text1: 'example@someline.com',
                    example_input_text2: 'Hey',
                    example_input_text3: null,

                    example_check_box: null,
                    example_check_box2: [],
                    example_check_box3: ['C'],

                    example_radio: null,
                    example_radio2: 'A',
                    example_radio3: 'custom_value',

                    example_custom_time: null,
                    example_switch: true,
                    example_select: 'custom_b',
                    example_select2: 'custom_b',

                    example_textarea: 'Go ahead..',
                    example_image: "https://www.someline.com/en/user/profilephoto/origin/f4ccc4de78c03fe2c321490cf6f8157f825e4c4f.jpg",
                    example_images: ["https://www.someline.com/en/user/profilephoto/origin/f4ccc4de78c03fe2c321490cf6f8157f825e4c4f.jpg"],

                    example_editor: '<p>A</p><p>B</p><p>C</p>',
                    example_editor_text: null,
                    example_editor_format_text: null,
                },

            }
        },
        computed: {},
        components: {},
        created () {
        },
        mounted(){

        },
        watch: {},
        events: {},
        methods: {
            openSomelineModal () {
                this.somelineModalVisible = true
            },
            handleEdit(index, row) {
                console.log(index, row);
            },
            handleDelete(index, row) {
                console.log(index, row);
            },
            getSearchValue(val){
                console.log('getSearchValue: ', val);
                if (val && val.length > 0) {
                    return 'a:' + val + '';
                } else {
                    return '';
                }
            },
            onResourceResponse(response){
                console.log('response: ', response);
                console.log('response url: ', response.url);
            },
            onFormSubmit(){
                console.log('onFormSubmit');

                this.isLoading = true;

                var resource = this.$resource('articles', {
//                    include: ''
                });

                resource.save({}, this.form_data)
                    .then((response) => {
                        console.log(response);

                        this.$message({
                            message: '保存成功',
                            type: 'success'
                        });

                        this.redirectToUrl('/console/articles');

                    }, (response) => {
                        console.log(response);

                        var error_message = '保存失败';
                        try {
                            var response_error_message = response.data.message;
                            if (response_error_message) {
                                console.error(response_error_message);
                                error_message = this.$options.filters.truncate(response_error_message, 80);
                            }
                        } catch (e) {
                            console.error(e.stack);
                        }

                        this.$message({
                            message: error_message,
                            type: 'error'
                        });
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });

            },
            onEditorConfig(editor){
                console.log('onEditorConfig');

                // set editor
                this.editor = editor;

            },
            onEditorReady(editor){
                console.log('onEditorReady');

                this.handleEditorText(editor);
            },
            onEditorChange(editor){
                console.log('onEditorChange');

                this.handleEditorText(editor);
            },
            handleEditorText(editor){

                var text = editor.$txt.text();
                console.log('text: ', text);
                this.form_data.example_editor_text = text;

                var formatText = editor.$txt.formatText();
                console.log('format text: ', formatText);
                this.form_data.example_editor_format_text = formatText;

            },
            handleTagCloseSimple(tag){
                console.log('handleClose', tag);

                this.simple_tag_items.splice(this.simple_tag_items.indexOf(tag), 1);
            },
            handleTagAddNewSimple(val){
                console.log('handleTagAddNew', val);

                this.simple_tag_items.push(val);
            },
            handleTagClose(tag){
                console.log('handleClose', tag);

                this.tag_items.splice(this.tag_items.indexOf(tag), 1);
            },
            handleTagAddNew(val){
                console.log('handleTagAddNew', val);

                this.tag_items.push({
                    tag: val
                });
            },
        },
    }
</script>