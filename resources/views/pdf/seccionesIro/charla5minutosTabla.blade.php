<table border="1" cellpadding="0" cellspacing="0">
    <tr>
        <td style="height:0px; border:0px;"></td>
		<td style="height:0px; border:0px;"></td>
		<td style="height:0px; border:0px;"></td>
		<td style="height:0px; border:0px;"></td>
    </tr>
	<tr>
		<td><p class="negrita">Ord.</p></td>
		<td><p class="negrita">Número</p></td>
		<td><p class="negrita">Descripción</p></td>
		<td><p class="negrita">Análisis Riesgo</p></td>
	</tr>
	

	@for ($i=0; $i < count($charla); $i++)
		<?php 
			$inci = "";
			$des = "";
			$analisis = "";
		?>

		@for ($j=0; $j < count($charla[$i]); $j++)
			<?php  
				$inci = $charla[$i][$j]->inci;
				if($charla[$i][$j]->pre == -1)
				{
					$des = $charla[$i][$j]->res;
				}
				else
				{
					$analisis = $analisis .  $charla[$i][$j]->res . ",";
				}
			?>
		@endfor
		<tr>
			<td><p>{{($i + 1)}}</p></td>
			<td><p>{{$inci}}</p></td>
			<td><p>{{$des}}</p></td>
			<td><p>{{$analisis}}</p></td>
		</tr>

	@endfor
	
	@for ($i=(count($charla)); $i < 8; $i++)
		<tr>
			<td><p>{{$i + 1}}</p></td>
			<td><p></p></td>
			<td><p></p></td>
			<td><p></p></td>
		</tr>
	@endfor
	
</table>