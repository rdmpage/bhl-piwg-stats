# BHL Australia

## Contributors

- Anthea Wallhead
- Australasian Native Orchid Society
- Australasian Systematic Botany Society
- Australian Biological Resources Study
- Australian Entomological Society
- Australian Garden History Society
- Australian Institute of Marine Science
- Australian Museum
- Australian National Herbarium
- Australian National Insect Collection
- Australian Network for Plant Conservation
- Bendigo Field Naturalists Club
- BHL Australia
- Board of the Botanic Gardens and State Herbarium, Adelaide
- Castlemaine Field Naturalists Club
- Commonwealth of Australia (Australian National Botanic Gardens)
- Commonwealth of Australia (Geoscience Australia)
- Entomological Society of Queensland
- Entomological Society of Victoria
- Field Naturalists Club of Victoria
- Field Naturalists Club of Ballarat
- Fossil Collectors Association of Australasia
- Geelong Field Naturalists Club
- Great Barrier Reef Marine Park Authority
- Hills Orchid Publishing
- Icewall One
- Latrobe Valley Naturalist Club
- Linnean Society of New South Wales
- Monash University Library Special Collections
- Museum and Art Gallery of the Northern Territory
- Museums Victoria
- North Queensland Natural History Group
- North Queensland Naturalists Club
- Northern Territory Field Naturalists Club
- Northern Territory Herbarium
- Peninsula Field Naturalists Club
- Queen Victoria Museum and Art Gallery
- Queensland Herbarium
- Queensland Museum
- Ringwood Field Naturalists Club
- Royal Botanic Gardens Victoria
- Royal Society of New South Wales
- Royal Society of Queensland
- Royal Society of Victoria
- Royal Society of Western Australia
- South Australian Museum
- State Library of New South Wales
- State Library of Victoria
- Sue Halliwell
- Tasmanian Field Naturalists Club
- Tasmanian Museum and Art Gallery
- The Malacological Society of Australasia
- The Royal Botanic Gardens and Domain Trust, New South Wales, Australia
- University of Melbourne
- Western Australian Herbarium
- Western Australian Museum
- Western Australian Naturalists’ Club (Inc.)
- Field Naturalists’ Club of Ballarat
- Field Naturalists’ Club of Ballarat

## Query

```
SELECT cited AS doi, COUNT(cited) as count, EntityID, item.ItemID, item.InstitutionName, part.Title, part.ContainerTitle 
	FROM citation
    INNER JOIN doi ON cited = doi.DOI
    INNER JOIN part ON EntityID = part.PartID
    INNER JOIN item ON part.ItemID = item.ItemID
    WHERE 
		cited LIKE '10.5962/p.%'
		AND item.InstitutionName IN ('Anthea Wallhead','Australasian Native Orchid Society','Australasian Systematic Botany Society','Australian Biological Resources Study','Australian Entomological Society','Australian Garden History Society','Australian Institute of Marine Science','Australian Museum','Australian National Herbarium','Australian National Insect Collection','Australian Network for Plant Conservation','Bendigo Field Naturalists Club','BHL Australia','Board of the Botanic Gardens and State Herbarium, Adelaide','Castlemaine Field Naturalists Club','Commonwealth of Australia (Australian National Botanic Gardens)','Commonwealth of Australia (Geoscience Australia)','Entomological Society of Queensland','Entomological Society of Victoria','Field Naturalists Club of Victoria','Field Naturalists'' Club of Ballarat','Fossil Collectors'' Association of Australasia','Geelong Field Naturalists Club','Great Barrier Reef Marine Park Authority','Hills Orchid Publishing','Icewall One','Latrobe Valley Naturalist Club','Linnean Society of New South Wales','Monash University Library Special Collections','Museum and Art Gallery of the Northern Territory','Museums Victoria','North Queensland Natural History Group','North Queensland Naturalists Club','Northern Territory Field Naturalists'' Club','Northern Territory Herbarium','Peninsula Field Naturalists'' Club','Queen Victoria Museum and Art Gallery','Queensland Herbarium','Queensland Museum','Ringwood Field Naturalists Club','Royal Botanic Gardens Victoria','Royal Society of New South Wales','Royal Society of Queensland','Royal Society of Victoria','Royal Society of Western Australia','South Australian Museum','State Library of New South Wales','State Library of Victoria','Sue Halliwell','Tasmanian Field Naturalists Club','Tasmanian Museum and Art Gallery','The Malacological Society of Australasia','The Royal Botanic Gardens and Domain Trust, New South Wales, Australia','University of Melbourne','Western Australian Herbarium','Western Australian Museum','Western Australian Naturalists’ Club (Inc.)','Field Naturalists’ Club of Ballarat','Field Naturalists’ Club of Ballarat')
		GROUP BY doi ORDER BY count DESC;
```

