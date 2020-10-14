{if $tsConfig.news}
<div id="top_msgs" class="container">
<i class="einet icon-mic" tl="1" title="Recent news."></i>
<ul id="top_news">
{foreach from=$tsConfig.news key=i item=n}
<li id="new_{$i+1}">{$n.not_body}</li>
{/foreach}
</ul>
<div class="clear"></div>
</div>
{/if}