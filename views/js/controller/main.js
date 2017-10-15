/**
 * @author Bertrand Chevrier <bertrand@taotesting.com>
 */
define([
    'module',
    'jquery',
    'lodash',
    'i18n',
    'context',
    'helpers',
    'uiForm',
    'layout/section',
    'layout/actions',
    'layout/tree',
    'layout/version-warning',
    'layout/loading-bar',
    'layout/nav',
    'layout/search',
    'ui/resource/selector',
    'provider/resources'
], function(module, $, _, __, context, helpers, uiForm, sections, actions, treeFactory, versionWarning, loadingBar, nav, search, resourceSelectorFactory, resourceProviderFactory) {
    'use strict';

    /**
     * This controller initialize all the layout components used by the backend : sections, actions, tree, loader, etc.
     * @exports tao/controller/main
     */
    return {
        start: function() {

            var $doc = $(document);

            versionWarning.init();

            //just before an ajax request
            $doc.ajaxSend(function() {
                loadingBar.start();
            });

            //when an ajax request complete
            $doc.ajaxComplete(function() {
                loadingBar.stop();
            });

            //navigation bindings
            nav.init();

            //search component
            search.init();

            //initialize sections
            sections.on('activate', function(section) {
                window.scrollTo(0, 0);

                // quick work around issue in IE11
                // IE randomly thinks there is no id and throws an error
                // I know it's not logical but with this 'fix' everything works fine
                if (!section || !section.id) {
                    return;
                }

                context.section = section.id;

                //initialize actions
                actions.init(section.panel);

                switch (section.type) {
                    case 'tree':
                        section.panel.addClass('content-panel');
                        //sectionHeight.init(section.panel);

                        //set up the tree
                        $('.taotree', section.panel).each(function() {
                            var $treeElt = $(this),
                                $actionBar = $('.tree-action-bar-box', section.panel);

                            var rootNode = $treeElt.data('rootnode');
                            var treeUrl = context.root_url;
                            var treeActions = {};
                            var serverParameters = {
                                extension: context.shownExtension,
                                perspective: context.shownStructure,
                                section: context.section,
                            };
                            var resourceProvider = resourceProviderFactory();
                            //if(rootNode){
                            //serverParameters.rootNode = rootNode;
                            //}
                            $.each($treeElt.data('actions'), function(key, val) {
                                if (actions.getBy(val)) {
                                    treeActions[key] = val;
                                }
                            });

                            if (/\/$/.test(treeUrl)) {
                                treeUrl += $treeElt.data('url').replace(/^\//, '');
                            } else {
                                treeUrl += $treeElt.data('url');
                            }
                            //treeFactory($treeElt, treeUrl, {
                            //serverParameters : serverParameters,
                            //actions : treeActions
                            //});
                            //$treeElt.on('ready.taotree', function() {
                            //$actionBar.addClass('active');
                            //sectionHeight.setHeights(section.panel);

                            actions.exec(treeActions.init, {
                                uri: rootNode
                            });

                            resourceProvider.getClasses(rootNode, serverParameters)
                                .then(function(classes) {
                                    resourceSelectorFactory($treeElt, {
                                        selectionMode: 'both',
                                        selectClass : true,
                                        showSelection : false,
                                        classUri: rootNode,
                                        classes: classes
                                    })
                                    .on('render', function() {
                                        $actionBar.addClass('active');
                                        //sectionHeight.setHeights(section.panel);

                                    })
                                    .on('query', function(params) {
                                        var self = this;

                                        //ask the server the resources from the component query
                                        resourceProvider.getResources(_.defaults(params, serverParameters))
                                            .then(function(items) {
                                                self.update(items, params);
                                            })
                                            .catch(function(err) {
                                                console.error(err);
                                            });
                                    })
                                    .on('change', function(selection) {
                                        _.forEach(selection, function(resource, uri) {
                                            if(resource.classUri){
                                                actions.updateContext({
                                                    uri: uri,
                                                    id: uri,
                                                    classUri: uri,

                                                    permissions: {
                                                        "item-authoring": true,
                                                        "item-class-new": true,
                                                        "item-delete": true,
                                                        "item-duplicate": true,
                                                        "item-export": true,
                                                        "item-import": true,
                                                        "item-new": true,
                                                        "item-preview": true,
                                                        "item-properties": true,
                                                        "item-translate": true
                                                    }
                                                });

                                                actions.exec(treeActions.selectClass, {
                                                    uri: uri,
                                                    id: uri,
                                                    classUri: uri
                                                });

                                            } else {
                                                actions.updateContext({
                                                    uri: uri,
                                                    id: uri,
                                                    classUri: rootNode,

                                                    permissions: {
                                                        "item-authoring": true,
                                                        "item-class-new": true,
                                                        "item-delete": true,
                                                        "item-duplicate": true,
                                                        "item-export": true,
                                                        "item-import": true,
                                                        "item-new": true,
                                                        "item-preview": true,
                                                        "item-properties": true,
                                                        "item-translate": true
                                                    }
                                                });

                                                actions.exec(treeActions.selectInstance, {
                                                    uri: uri,
                                                    id: uri,
                                                    classUri: rootNode
                                                });
                                            }
                                        });

                                    });
                            })
                            .catch(function(err) {
                                console.error(err);
                            });

                        });

                        $('.navi-container', section.panel).show();
                        break;
                    case 'content':

                        //or load the content block
                        this.loadContentBlock();

                        break;
                }
            })
            .init();


            //initialize legacy components
            helpers.init();
            uiForm.init();
        }
    };
});
