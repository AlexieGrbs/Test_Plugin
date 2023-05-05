{if isset(PRODUCED_BY_VALUE)}
  <div class="fieldgroup">
    <label for="produced_by">{l s='Produced by'}:</label>
    <div>
      <input type="text" name="produced_by" id="produced_by" size="50" value="{$smarty.post.produced_by|htmlspecialchars}" />
    </div>
  </div>
{else}
  <li><a href="{$current_url}&amp;action=edit_metadata&amp;cat_id={$image.category.id}&amp;image_id={$image.id}&amp;type=image&amp;tab=info#edit_metadata">{l s='Produced by'}</a></li>
{/if}