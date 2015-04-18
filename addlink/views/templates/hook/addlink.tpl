<section class="footer-block col-xs-12 col-sm-2" id="block_various_links_footer">
		<h4 class="title_block">{l s=$title mod='addlink'}</h4>
		<ul class="toggle-footer" style="">

{if $link_name != ''}<li><a href="{$link}">{l mod='addlink'} {$link_name|escape:'html':'UTF-8'}</a></li>{/if}
{if $link_name != ''}<li><a href="{$link1}">{l mod='addlink'} {$link_name1|escape:'html':'UTF-8'}</a></li>{/if}

		</ul>
</section>