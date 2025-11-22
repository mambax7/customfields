<{include file="db:publisher_header.tpl" item=$item|default:false}>

<{if $op|default:false == 'preview'}>
    <br>
    <{include file="db:publisher_singleitem.tpl" item=$item}>
<{/if}>

<div class="publisher_infotitle"><{$langIntroTitle|default:''}></div>
<div class="publisher_infotext"><{$langIntroText|default:''}></div>
<br><{$form.javascript}>

<div id="tabs">
    <ul>
        <{foreach item=tab key=key from=$form.tabs}>
            <li><a href="#tab_<{$key}>"><span><{$tab}></span></a></li>
        <{/foreach}>
    </ul>

    <form name="<{$form.name}>" action="<{$form.action}>" method="<{$form.method}>"<{$form.extra}>><!-- start of form elements loop -->
        <{foreach item=tab key=key from=$form.tabs}>
            <div id="tab_<{$key}>">
                <table class="outer" cellspacing="1">
                    <{foreach item=element from=$form.elements}>
                    <{if $element.tab|default:'' == $key || $element.tab|default:0 == -1}>
                         <{if !$element.hidden|default:false}>
                            <tr>
                                <td class="head" width="30%">
                                    <{if $element.caption|default:false|default:'' != ''}>
                                        <div class='xoops-form-element-caption<{if $element.required}>-required<{/if}>'>
                                            <span class='caption-text'><{$element.caption}></span>
                                            <{if $element.required}>
                                                <span class='caption-marker'>*</span>
                                            <{/if}>
                                        </div>
                                    <{/if}> <{if $element.description|default:false}>
                                        <div style="font-weight: normal; font-size:small;"><{$element.description}></div>
                                    <{/if}>
                                </td>
                                <td class="<{cycle values=" even,odd"}>"><{$element.body}></td>
                            </tr>
                        <{/if}>
                    <{/if}>
                    <{/foreach}><!-- end of form elements loop -->
                </table>
            </div>
        <{/foreach}>
        <{foreach item=element from=$form.elements}>
            <{if $element.hidden|default:false}>
                <{$element.body}>
            <{/if}>
        <{/foreach}>
        
        <!-- İLAVE ALANLAR / CUSTOM FIELDS -->
        <{if $customfields_form}>
        <div class="customfields-section" style="margin: 20px 0; padding: 20px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px;">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: #495057; font-size: 18px; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                <i class="fa fa-plus-circle"></i> İlave Alanlar
            </h3>
            <{$customfields_form}>
        </div>
        <{/if}>
    </form>
</div>

<{if $isAdmin|default:0 == 1}>
    <div class="publisher_adminlinks"><{$publisher_adminpage}></div>
<{/if}>