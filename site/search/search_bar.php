                                <form action="search.php">
                                        <input id="search_box" class="search_box" type="text" name="query" value="<?php if(isset($query) echo $query; ?>"/>
                                        <input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
                                        <input class="search_button" type="submit" value="Search!" />
                                <script language="javascript" type="text/javascript">
									$("#search_box").coolautosuggest({
									  url:"../api/search/suggest.php?key=",
									  submitOnSelect:true
									});
                                </script>
                                </form>
