# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: comprehensive-verification.spec.ts >> Comprehensive Independent Verification >> CSS/JS Asset Verification >> production CSS and JS bundles load
- Location: tests\browser\comprehensive-verification.spec.ts:324:5

# Error details

```
Error: expect(received).toBeGreaterThan(expected)

Expected: > 0
Received:   0
```

# Page snapshot

```yaml
- generic [active] [ref=e1]:
  - banner [ref=e2]:
    - generic [ref=e3]:
      - link "Laravel — Go to homepage" [ref=e4] [cursor=pointer]:
        - /url: http://127.0.0.1:8000
        - generic [ref=e8]: DonateBazaar
      - navigation "Primary navigation" [ref=e9]:
        - link "Home" [ref=e10] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
        - link "Campaigns" [ref=e11] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/all-campaigns
        - button "About" [ref=e12] [cursor=pointer]
        - link "Contact" [ref=e15] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/contact
      - generic [ref=e16]:
        - link "Search" [ref=e17] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/search
        - link "Log in" [ref=e22] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/login
        - link "Get Started" [ref=e24] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/register
  - main [ref=e26]:
    - generic [ref=e27]:
      - generic [ref=e28]:
        - img "Be Someone's Hope Today" [ref=e29]
        - generic [ref=e31]:
          - generic [ref=e32]: Trusted by 50,000+ Donors Across India
          - heading [level=1] [ref=e34]:
            - text: Be Someone's
            - emphasis [ref=e35]: Hope Today
          - paragraph [ref=e36]: Stand with people in crisis — from medical emergencies to education and disasters — every rupee can change a life.
          - generic [ref=e37]:
            - link "Donate Now" [ref=e38] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/all-campaigns
            - link "Start Fundraiser" [ref=e42] [cursor=pointer]:
              - /url: /campaign/create
      - generic [ref=e49]:
        - generic [ref=e50]: Change a Child's Life
        - heading [level=1] [ref=e52]:
          - text: Together Save
          - emphasis [ref=e53]: Precious Lives
        - paragraph [ref=e54]: Help children access education, nutrition, and the care they deserve — creating brighter futures across every corner of India.
        - generic [ref=e55]:
          - link [ref=e56] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/all-campaigns
            - generic [ref=e57]: Donate Now
          - link [ref=e61] [cursor=pointer]:
            - /url: /campaign/create
            - generic [ref=e62]: Start Fundraiser
      - generic [ref=e69]:
        - generic [ref=e70]: Be the Change
        - heading [level=1] [ref=e72]:
          - text: Be the Reason
          - emphasis [ref=e73]: Someone Smiles
        - paragraph [ref=e74]: Begin your journey of giving today — make a lasting difference in someone's life with complete transparency and trust.
        - generic [ref=e75]:
          - link [ref=e76] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/all-campaigns
            - generic [ref=e77]: Donate Now
          - link [ref=e81] [cursor=pointer]:
            - /url: /campaign/create
            - generic [ref=e82]: Start Fundraiser
      - generic [ref=e86]:
        - generic [ref=e87]:
          - generic [ref=e88]: Funds Raised
          - generic [ref=e94]: ₹10 Cr+
          - generic [ref=e95]: and growing every day
        - generic [ref=e96]:
          - generic [ref=e97]: Donors
          - generic [ref=e105]: 50K+
          - generic [ref=e106]: across all 28 states
        - generic [ref=e107]:
          - generic [ref=e108]: Campaigns
          - generic [ref=e113]: 2,000+
          - generic [ref=e114]: active & verified
      - button "Previous" [ref=e115] [cursor=pointer]
      - button "Next" [ref=e118] [cursor=pointer]
      - generic [ref=e121]:
        - button [ref=e122] [cursor=pointer]
        - button [ref=e123] [cursor=pointer]
        - button [ref=e124] [cursor=pointer]
      - link "Scroll to campaigns" [ref=e125] [cursor=pointer]:
        - /url: "#campaigns"
        - generic [ref=e128]: Explore Causes
      - generic [ref=e129]:
        - generic [ref=e130]:
          - generic [ref=e131]: 85,09,423
          - generic [ref=e132]: Funds Raised
        - generic [ref=e133]:
          - generic [ref=e134]: 42,547
          - generic [ref=e135]: Generous Donors
        - generic [ref=e136]:
          - generic [ref=e137]: 1,702
          - generic [ref=e138]: Campaigns
        - generic [ref=e139]:
          - generic [ref=e140]: 100%
          - generic [ref=e141]: Transparent
    - generic [ref=e143]:
      - generic [ref=e144]: Trusted by 2.5 Million Donors
      - generic [ref=e146]: 10,000+ Verified NGOs
      - generic [ref=e148]: Regular Updates
      - generic [ref=e150]: Multiple Causes
      - generic [ref=e152]: Product Giving
      - generic [ref=e154]: Secure Payments
      - generic [ref=e156]: 24x7 Support
      - generic [ref=e158]: Trusted by 2.5 Million Donors
      - generic [ref=e160]: 10,000+ Verified NGOs
      - generic [ref=e162]: Regular Updates
      - generic [ref=e164]: Multiple Causes
      - generic [ref=e166]: Product Giving
      - generic [ref=e168]: Secure Payments
      - generic [ref=e170]: 24x7 Support
    - generic [ref=e173]:
      - generic [ref=e174]:
        - generic [ref=e175]: Browse by cause
        - heading "Explore Our Categories" [level=2] [ref=e176]
        - paragraph [ref=e177]: Discover causes that need your support — find what moves you.
      - generic [ref=e178]:
        - link " Elderly Support 0 Campaigns" [ref=e179] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/elderly-support
          - generic [ref=e180]: 
          - generic [ref=e182]: Elderly Support
          - generic [ref=e183]: 0 Campaigns
        - link " Environment & Climate 0 Campaigns" [ref=e184] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/environment-climate
          - generic [ref=e185]: 
          - generic [ref=e187]: Environment & Climate
          - generic [ref=e188]: 0 Campaigns
        - link " Animal Rescue 0 Campaigns" [ref=e189] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/animal-rescue
          - generic [ref=e190]: 
          - generic [ref=e192]: Animal Rescue
          - generic [ref=e193]: 0 Campaigns
        - link " Disaster Relief 0 Campaigns" [ref=e194] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/disaster-relief
          - generic [ref=e195]: 
          - generic [ref=e197]: Disaster Relief
          - generic [ref=e198]: 0 Campaigns
        - link " Women Empowerment 0 Campaigns" [ref=e199] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/women-empowerment
          - generic [ref=e200]: 
          - generic [ref=e202]: Women Empowerment
          - generic [ref=e203]: 0 Campaigns
        - link " Child Care 0 Campaigns" [ref=e204] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/child-care
          - generic [ref=e205]: 
          - generic [ref=e207]: Child Care
          - generic [ref=e208]: 0 Campaigns
        - link " Shelter & Housing 0 Campaigns" [ref=e209] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/shelter-housing
          - generic [ref=e210]: 
          - generic [ref=e212]: Shelter & Housing
          - generic [ref=e213]: 0 Campaigns
        - link " Hunger & Food 0 Campaigns" [ref=e214] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/hunger-food
          - generic [ref=e215]: 
          - generic [ref=e217]: Hunger & Food
          - generic [ref=e218]: 0 Campaigns
        - link " Education Support 0 Campaigns" [ref=e219] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/education-support
          - generic [ref=e220]: 
          - generic [ref=e222]: Education Support
          - generic [ref=e223]: 0 Campaigns
        - link " Medical Emergency 0 Campaigns" [ref=e224] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/medical-emergency
          - generic [ref=e225]: 
          - generic [ref=e227]: Medical Emergency
          - generic [ref=e228]: 0 Campaigns
        - link " New 0 Campaigns" [ref=e229] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/new
          - generic [ref=e230]: 
          - generic [ref=e232]: New
          - generic [ref=e233]: 0 Campaigns
        - link " Test test 0 Campaigns" [ref=e234] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/test-test
          - generic [ref=e235]: 
          - generic [ref=e237]: Test test
          - generic [ref=e238]: 0 Campaigns
        - link " Medical 0 Campaigns" [ref=e239] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/medical
          - generic [ref=e240]: 
          - generic [ref=e242]: Medical
          - generic [ref=e243]: 0 Campaigns
        - link " Medical testing 0 Campaigns" [ref=e244] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/medical-testing
          - generic [ref=e245]: 
          - generic [ref=e247]: Medical testing
          - generic [ref=e248]: 0 Campaigns
        - link " Animal Safe 0 Campaigns" [ref=e249] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/category/animal-safe
          - generic [ref=e250]: 
          - generic [ref=e252]: Animal Safe
          - generic [ref=e253]: 0 Campaigns
    - generic [ref=e255]:
      - generic [ref=e256]:
        - generic [ref=e257]: Make an impact
        - heading "Featured Campaigns" [level=2] [ref=e258]
        - paragraph [ref=e259]: Support urgent and impactful causes across India.
      - generic [ref=e261]:
        - button "All" [ref=e262] [cursor=pointer]
        - button "Elderly Support" [ref=e264] [cursor=pointer]
        - button "Environment & Climate" [ref=e266] [cursor=pointer]
        - button "Animal Rescue" [ref=e268] [cursor=pointer]
        - button "Disaster Relief" [ref=e270] [cursor=pointer]
        - button "Women Empowerment" [ref=e272] [cursor=pointer]
        - button "Child Care" [ref=e274] [cursor=pointer]
        - button "Shelter & Housing" [ref=e276] [cursor=pointer]
        - button "Hunger & Food" [ref=e278] [cursor=pointer]
        - button "Education Support" [ref=e280] [cursor=pointer]
        - button "Medical Emergency" [ref=e282] [cursor=pointer]
        - button "New" [ref=e284] [cursor=pointer]
        - button "Test test" [ref=e286] [cursor=pointer]
        - button "Medical" [ref=e288] [cursor=pointer]
        - button "Medical testing" [ref=e290] [cursor=pointer]
        - button "Animal Safe" [ref=e292] [cursor=pointer]
      - generic [ref=e294]: Scroll to load more
    - generic [ref=e299]:
      - generic [ref=e300]:
        - generic [ref=e301]:
          - img "A child receiving support from a campaign" [ref=e302]
          - generic [ref=e303]: Live story
        - generic [ref=e305]:
          - generic [ref=e306]: "1"
          - generic [ref=e307]:
            - generic [ref=e308]: donation
            - generic [ref=e309]: becomes a story
      - generic [ref=e310]:
        - generic [ref=e311]: Our Story
        - heading [level=2] [ref=e312]:
          - text: Every gift writesa
          - emphasis [ref=e313]: new chapter
          - text: .
        - paragraph [ref=e314]: When Aarav's village lost its only well, a single campaign turned strangers into neighbours. Today, 240 families drink clean water — and one small act became a story the whole community tells.
        - generic [ref=e315]:
          - generic [ref=e316]: “
          - paragraph [ref=e317]: I never met the people who helped us. But my children will grow up knowing kindness has no address.
          - generic [ref=e318]: — Meera, Aarav's mother
        - link "Read more stories" [ref=e319] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/campaigns
    - generic [ref=e324]:
      - generic [ref=e325]:
        - generic [ref=e326]: Simple, Transparent, Secure
        - heading "How It Works" [level=2] [ref=e327]
        - paragraph [ref=e328]: Giving should feel good — not complicated. Here is how DonateBazaar makes it effortless in three steps.
      - generic [ref=e329]:
        - generic [ref=e330]:
          - generic [ref=e331]: "01"
          - generic [ref=e332]:
            - generic: "01"
            - generic [ref=e337]: Choose Your Cause
            - generic [ref=e338]: Browse hundreds of verified campaigns by category — medical, education, disaster relief, and more. Every campaign is personally reviewed before going live.
            - generic [ref=e339]:
              - generic [ref=e340]: ✦ Verified NGOs
              - generic [ref=e341]: ✦ 10+ Categories
              - generic [ref=e342]: ✦ Real Stories
        - generic [ref=e343]:
          - generic [ref=e344]: "02"
          - generic [ref=e345]:
            - generic: "02"
            - generic [ref=e349]: Donate Securely
            - generic [ref=e350]: Use UPI, cards, or net banking — whatever is most convenient. Every transaction is encrypted end-to-end through RBI-compliant payment gateways.
            - generic [ref=e351]:
              - generic [ref=e352]: ✦ UPI & Cards
              - generic [ref=e353]: ✦ 256-bit SSL
              - generic [ref=e354]: ✦ RBI Compliant
        - generic [ref=e355]:
          - generic [ref=e356]: "03"
          - generic [ref=e357]:
            - generic: "03"
            - generic [ref=e361]: Track Your Impact
            - generic [ref=e362]: Receive real-time updates from campaign creators. Watch your contribution turn into measurable, lasting change — every rupee tracked with full transparency.
            - generic [ref=e363]:
              - generic [ref=e364]: ✦ Live Updates
              - generic [ref=e365]: ✦ Photo Reports
              - generic [ref=e366]: ✦ Tax Receipts
      - link "Start Donating Now" [ref=e368] [cursor=pointer]:
        - /url: http://127.0.0.1:8000/all-campaigns
    - generic [ref=e373]:
      - generic [ref=e374]:
        - generic [ref=e375]: Love from our community
        - heading "Testimonials" [level=2] [ref=e376]
      - generic [ref=e377]:
        - button "Donors" [ref=e378] [cursor=pointer]
        - button "NGOs" [ref=e380] [cursor=pointer]
        - button "Celebrities" [ref=e382] [cursor=pointer]
      - generic [ref=e386]:
        - generic [ref=e387]:
          - generic [ref=e388]: "\""
          - generic [ref=e389]: Contributed 3 Times
          - paragraph [ref=e390]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e391]:
            - generic [ref=e392]: D
            - generic [ref=e393]:
              - generic [ref=e394]: Donor 1
              - generic [ref=e395]: Supporter
        - generic [ref=e396]:
          - generic [ref=e397]: "\""
          - generic [ref=e398]: Contributed 4 Times
          - paragraph [ref=e399]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e400]:
            - generic [ref=e401]: D
            - generic [ref=e402]:
              - generic [ref=e403]: Donor 2
              - generic [ref=e404]: Supporter
        - generic [ref=e405]:
          - generic [ref=e406]: "\""
          - generic [ref=e407]: Contributed 5 Times
          - paragraph [ref=e408]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e409]:
            - generic [ref=e410]: D
            - generic [ref=e411]:
              - generic [ref=e412]: Donor 3
              - generic [ref=e413]: Supporter
        - generic [ref=e414]:
          - generic [ref=e415]: "\""
          - generic [ref=e416]: Contributed 6 Times
          - paragraph [ref=e417]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e418]:
            - generic [ref=e419]: D
            - generic [ref=e420]:
              - generic [ref=e421]: Donor 4
              - generic [ref=e422]: Supporter
        - generic [ref=e423]:
          - generic [ref=e424]: "\""
          - generic [ref=e425]: Contributed 7 Times
          - paragraph [ref=e426]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e427]:
            - generic [ref=e428]: D
            - generic [ref=e429]:
              - generic [ref=e430]: Donor 5
              - generic [ref=e431]: Supporter
        - generic [ref=e432]:
          - generic [ref=e433]: "\""
          - generic [ref=e434]: Contributed 8 Times
          - paragraph [ref=e435]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e436]:
            - generic [ref=e437]: D
            - generic [ref=e438]:
              - generic [ref=e439]: Donor 6
              - generic [ref=e440]: Supporter
        - generic [ref=e441]:
          - generic [ref=e442]: "\""
          - generic [ref=e443]: Contributed 9 Times
          - paragraph [ref=e444]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e445]:
            - generic [ref=e446]: D
            - generic [ref=e447]:
              - generic [ref=e448]: Donor 7
              - generic [ref=e449]: Supporter
        - generic [ref=e450]:
          - generic [ref=e451]: "\""
          - generic [ref=e452]: Contributed 10 Times
          - paragraph [ref=e453]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e454]:
            - generic [ref=e455]: D
            - generic [ref=e456]:
              - generic [ref=e457]: Donor 8
              - generic [ref=e458]: Supporter
        - generic [ref=e459]:
          - generic [ref=e460]: "\""
          - generic [ref=e461]: Contributed 11 Times
          - paragraph [ref=e462]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e463]:
            - generic [ref=e464]: D
            - generic [ref=e465]:
              - generic [ref=e466]: Donor 9
              - generic [ref=e467]: Supporter
        - generic [ref=e468]:
          - generic [ref=e469]: "\""
          - generic [ref=e470]: Contributed 12 Times
          - paragraph [ref=e471]: Donating here makes me happy. Helping others is the greatest joy I have experienced on this platform.
          - generic [ref=e472]:
            - generic [ref=e473]: D
            - generic [ref=e474]:
              - generic [ref=e475]: Donor 10
              - generic [ref=e476]: Supporter
    - generic [ref=e478]:
      - generic [ref=e479]:
        - generic [ref=e480]: 6 Reasons of assurance
        - heading "Why DonateBazaar?" [level=2] [ref=e481]
        - paragraph [ref=e482]: Trusted platform for transparent, secure, and impactful donations.
      - generic [ref=e483]:
        - generic [ref=e484]:
          - img "Product Giving" [ref=e486]
          - generic [ref=e487]:
            - generic [ref=e488]: Product Giving
            - generic [ref=e489]: Make your impact tangible by donating products directly.
        - generic [ref=e490]:
          - img "Verified & Trusted" [ref=e492]
          - generic [ref=e493]:
            - generic [ref=e494]: Verified & Trusted
            - generic [ref=e495]: Support charities through strict verification processes.
        - generic [ref=e496]:
          - img "Guaranteed Updates" [ref=e498]
          - generic [ref=e499]:
            - generic [ref=e500]: Guaranteed Updates
            - generic [ref=e501]: Stay informed with regular campaign progress updates.
        - generic [ref=e502]:
          - img "Easy Setup" [ref=e504]
          - generic [ref=e505]:
            - generic [ref=e506]: Easy Setup
            - generic [ref=e507]: Launch your fundraiser in just a few minutes.
        - generic [ref=e508]:
          - img "Secure & Private" [ref=e510]
          - generic [ref=e511]:
            - generic [ref=e512]: Secure & Private
            - generic [ref=e513]: Encrypted payments and protected donor data always.
        - generic [ref=e514]:
          - img "24×7 Support" [ref=e516]
          - generic [ref=e517]:
            - generic [ref=e518]: 24×7 Support
            - generic [ref=e519]: Our team is always here to help you succeed.
    - generic [ref=e522]:
      - heading "Start Your Fundraiser Today" [level=2] [ref=e523]
      - paragraph [ref=e524]: Start raising funds for urgent needs like medical care, education, and disaster relief — it takes just a few minutes to make a difference.
      - link "Start Fundraiser" [ref=e525] [cursor=pointer]:
        - /url: /campaign/create
    - generic [ref=e528]:
      - generic [ref=e529]:
        - generic [ref=e530]: From the Community
        - heading "Stories & Perspectives" [level=2] [ref=e531]
        - paragraph [ref=e532]: Real voices on real causes — insights and ideas from our writers across India.
      - generic [ref=e533]:
        - generic [ref=e535]:
          - link "Why Online Donations Are Transforming Charitable Giving in Medical ★ Featured 3 min read · 1 month ago Why Online Donations Are Transforming Charitable Giving in new Blognew Blognew Blognew Blognew Blognew Blognew Blognew Blognew Blognew Blog A Anonymous 10 1" [ref=e536] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/blog/why-online-donations-are-transforming-charitable-giving-in
            - generic [ref=e537]:
              - img "Why Online Donations Are Transforming Charitable Giving in" [ref=e538]
              - generic [ref=e539]: Medical
              - generic [ref=e540]: ★ Featured
            - generic [ref=e541]:
              - generic [ref=e542]: 3 min read · 1 month ago
              - generic [ref=e546]: Why Online Donations Are Transforming Charitable Giving in
              - paragraph [ref=e547]: new Blognew Blognew Blognew Blognew Blognew Blognew Blognew Blognew Blognew Blog
              - generic [ref=e548]:
                - generic [ref=e549]:
                  - generic [ref=e550]: A
                  - generic [ref=e551]: Anonymous
                - generic [ref=e552]:
                  - generic [ref=e553]: "10"
                  - generic [ref=e557]: "1"
          - 'link "Environment and Climate: Why the Difference Matters More Than Ever Post Environment & Climate 4 min read · 2 months ago Environment and Climate: Why the Difference Matters More Than Ever Post Climate refers to the long-term pattern of weather conditions in a particular region. Unlike weather, which changes dail... A Anonymous 9 1" [ref=e560] [cursor=pointer]':
            - /url: http://127.0.0.1:8000/blog/environment-and-climate-why-the-difference-matters-more-than-ever-post
            - generic [ref=e561]:
              - 'img "Environment and Climate: Why the Difference Matters More Than Ever Post" [ref=e562]'
              - generic [ref=e563]: Environment & Climate
            - generic [ref=e564]:
              - generic [ref=e565]: 4 min read · 2 months ago
              - generic [ref=e569]: "Environment and Climate: Why the Difference Matters More Than Ever Post"
              - paragraph [ref=e570]: Climate refers to the long-term pattern of weather conditions in a particular region. Unlike weather, which changes dail...
              - generic [ref=e571]:
                - generic [ref=e572]:
                  - generic [ref=e573]: A
                  - generic [ref=e574]: Anonymous
                - generic [ref=e575]:
                  - generic [ref=e576]: "9"
                  - generic [ref=e580]: "1"
          - link "Blog For Testing Disaster Relief 1 min read · 2 months ago Blog For Testing Blog For TestingBlog For TestingBlog For TestingBlog For TestingBlog For TestingBlog For Testing A Anonymous 13 0" [ref=e583] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/blog/blog-for-testing
            - generic [ref=e584]:
              - img "Blog For Testing" [ref=e585]
              - generic [ref=e586]: Disaster Relief
            - generic [ref=e587]:
              - generic [ref=e588]: 1 min read · 2 months ago
              - generic [ref=e592]: Blog For Testing
              - paragraph [ref=e593]: Blog For TestingBlog For TestingBlog For TestingBlog For TestingBlog For TestingBlog For Testing
              - generic [ref=e594]:
                - generic [ref=e595]:
                  - generic [ref=e596]: A
                  - generic [ref=e597]: Anonymous
                - generic [ref=e598]:
                  - generic [ref=e599]: "13"
                  - generic [ref=e603]: "0"
        - generic [ref=e606]:
          - button [disabled]
          - button "Slide 1" [ref=e608] [cursor=pointer]
          - button [disabled]
      - link "View all posts" [ref=e610] [cursor=pointer]:
        - /url: http://127.0.0.1:8000/blog
    - generic [ref=e615]:
      - generic [ref=e616]:
        - generic [ref=e617]: Our Reach
        - heading [level=2] [ref=e618]:
          - text: Impact Across
          - emphasis [ref=e619]: India
        - paragraph [ref=e620]: Together with our supporters, we are transforming lives across multiple states.
      - generic [ref=e621]:
        - generic [ref=e622]:
          - generic [ref=e623]:
            - generic [ref=e624]: Coverage Map
            - img "Impact Map" [ref=e625]
          - generic [ref=e626]:
            - generic [ref=e627]:
              - generic [ref=e628]: "28"
              - generic [ref=e629]: States Reached
            - generic [ref=e630]:
              - generic [ref=e631]: 452,668
              - generic [ref=e632]: Total Lives
            - generic [ref=e633]:
              - generic [ref=e634]: "686"
              - generic [ref=e635]: Districts Covered
            - generic [ref=e636]:
              - generic [ref=e637]: 500+
              - generic [ref=e638]: NGO Partners
        - generic [ref=e639]:
          - generic [ref=e640]: Lives Impacted by State
          - generic [ref=e641]:
            - generic [ref=e642]: "1"
            - generic [ref=e643]: Uttarakhand
            - generic [ref=e646]: 56,522
          - generic [ref=e647]:
            - generic [ref=e648]: "2"
            - generic [ref=e649]: Haryana
            - generic [ref=e652]: 55,950
          - generic [ref=e653]:
            - generic [ref=e654]: "3"
            - generic [ref=e655]: Rajasthan
            - generic [ref=e658]: 51,040
          - generic [ref=e659]:
            - generic [ref=e660]: "4"
            - generic [ref=e661]: Andhra Pradesh
            - generic [ref=e664]: 36,560
          - generic [ref=e665]:
            - generic [ref=e666]: "5"
            - generic [ref=e667]: Assam
            - generic [ref=e670]: 23,443
          - generic [ref=e671]:
            - generic [ref=e672]: "6"
            - generic [ref=e673]: West Bengal
            - generic [ref=e676]: 42,547
          - generic [ref=e677]:
            - generic [ref=e678]: "7"
            - generic [ref=e679]: Bengaluru
            - generic [ref=e682]: 55,311
          - generic [ref=e683]:
            - generic [ref=e684]: "8"
            - generic [ref=e685]: Chennai
            - generic [ref=e688]: 63,821
  - contentinfo [ref=e689]:
    - generic [ref=e690]:
      - generic [ref=e691]:
        - generic [ref=e692]:
          - generic [ref=e693]: Live campaigns
          - generic [ref=e695]:
            - heading "Ready to Make an Impact?" [level=2] [ref=e696]
            - paragraph [ref=e697]: Join thousands of donors changing lives every single day.
          - generic [ref=e698]:
            - generic [ref=e699]:
              - generic [ref=e700]: "0"
              - generic [ref=e701]: Donors
            - generic [ref=e702]:
              - generic [ref=e703]: ₹0
              - generic [ref=e704]: Raised
            - generic [ref=e705]:
              - generic [ref=e706]: "0"
              - generic [ref=e707]: Campaigns
        - generic [ref=e708]:
          - link "Explore Campaigns" [ref=e709] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/all-campaigns
          - link "Start a Fundraiser →" [ref=e713] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/campaign/create
            - generic [ref=e714]:
              - text: Start a Fundraiser
              - generic [ref=e715]: →
      - generic [ref=e716]:
        - generic [ref=e717]:
          - generic [ref=e718]: DonateBazaar
          - paragraph [ref=e719]: A trusted platform connecting donors with verified causes. Transparent, secure, and impactful giving for a better world.
          - generic [ref=e720]: 2.5M+ donors trust us
          - generic [ref=e722]:
            - link [ref=e723] [cursor=pointer]:
              - /url: https://www.facebook.com/
            - link [ref=e727] [cursor=pointer]:
              - /url: https://x.com/
            - link [ref=e731] [cursor=pointer]:
              - /url: https://www.instagram.com/
            - link [ref=e736] [cursor=pointer]:
              - /url: https://www.linkedin.com/
            - link [ref=e742] [cursor=pointer]:
              - /url: https://www.youtube.com/
        - generic [ref=e747]:
          - heading "Platform" [level=3] [ref=e748]
          - list [ref=e749]:
            - listitem [ref=e750]:
              - link "Home" [ref=e751] [cursor=pointer]:
                - /url: http://127.0.0.1:8000
            - listitem [ref=e752]:
              - link "Campaigns" [ref=e753] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/all-campaigns
            - listitem [ref=e754]:
              - link "Start Fundraiser" [ref=e755] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/campaign/create
            - listitem [ref=e756]:
              - link "Search" [ref=e757] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/search
            - listitem [ref=e758]:
              - link "Impact Stories" [ref=e759] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/impact
            - listitem [ref=e760]:
              - link "How It Works" [ref=e761] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/how-it-works
            - listitem [ref=e762]:
              - link "Disaster Relief" [ref=e763] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/disaster-relief
        - generic [ref=e764]:
          - heading "Company" [level=3] [ref=e765]
          - list [ref=e766]:
            - listitem [ref=e767]:
              - link "About Us" [ref=e768] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/about
            - listitem [ref=e769]:
              - link "Contact" [ref=e770] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/contact
            - listitem [ref=e771]:
              - link "Careers" [ref=e772] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/career
            - listitem [ref=e773]:
              - link "Blog" [ref=e774] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/blog
            - listitem [ref=e775]:
              - link "Partnership" [ref=e776] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/partnership
        - generic [ref=e777]:
          - heading "Stay Updated" [level=3] [ref=e778]
          - paragraph [ref=e779]: Get inspiring stories, new campaigns, and impact reports — straight to your inbox.
          - generic [ref=e780]:
            - generic [ref=e781]: Your email
            - generic [ref=e783]:
              - textbox "Email for newsletter" [ref=e784]:
                - /placeholder: Your email
              - button "Subscribe" [ref=e785] [cursor=pointer]
          - generic [ref=e787]:
            - generic [ref=e788]: 10K+ subscribers
            - generic [ref=e790]: No spam, ever
      - generic [ref=e793]:
        - generic [ref=e794]: © 2026 DonateBazaar. All rights reserved.
        - generic [ref=e795]:
          - text: Made with
          - generic [ref=e796]: ♥
          - text: for a better world
        - generic [ref=e797]:
          - generic [ref=e798]:
            - link "Privacy" [ref=e799] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/privacy-policy
            - link "Terms" [ref=e800] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/terms-of-service
            - link "Refunds" [ref=e801] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/refund-cancellation
            - link "Cookies" [ref=e802] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/cookie-policy
            - link "FAQ" [ref=e803] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/faq
          - button "Back to top" [ref=e804] [cursor=pointer]
  - button "Open chat" [ref=e809] [cursor=pointer]:
    - generic [ref=e810]: 
```

# Test source

```ts
  251 | 
  252 |       await page.goto('/');
  253 |       await page.waitForLoadState('domcontentloaded');
  254 | 
  255 |       console.log('=== CONSOLE ERRORS ===');
  256 |       consoleErrors.forEach(e => console.log(e));
  257 |       console.log('=== CONSOLE WARNINGS ===');
  258 |       consoleWarnings.forEach(w => console.log(w));
  259 |       console.log('=== NETWORK ERRORS >=400 ===');
  260 |       networkErrors.forEach(n => console.log(`${n.status} ${n.url}`));
  261 | 
  262 |       const appErrors = consoleErrors.filter(e =>
  263 |         !e.includes('unpkg.com') &&
  264 |         !e.includes('cdn.jsdelivr.net') &&
  265 |         !e.includes('cdnjs.cloudflare.com') &&
  266 |         !e.includes('cdn.lordicon.com') &&
  267 |         !e.includes('aos.css') &&
  268 |         !e.includes('aos.js') &&
  269 |         !e.includes('swiper') &&
  270 |         !e.includes('lottie-player') &&
  271 |         !e.includes('vanilla-tilt') &&
  272 |         !e.includes('lucide') &&
  273 |         !e.includes('127.0.0.1:5173')
  274 |       );
  275 |       expect(appErrors.length).toBe(0);
  276 |     });
  277 |   });
  278 | 
  279 |   test.describe('Responsive UI Verification', () => {
  280 |     test('no horizontal overflow on mobile', async ({ page }) => {
  281 |       await page.setViewportSize({ width: 390, height: 844 });
  282 |       await page.goto('/');
  283 |       await page.waitForLoadState('domcontentloaded');
  284 |       const hasOverflow = await page.evaluate(() => {
  285 |         return document.documentElement.scrollWidth > window.innerWidth;
  286 |       });
  287 |       expect(hasOverflow).toBe(false);
  288 |     });
  289 | 
  290 |     test('no horizontal overflow on tablet', async ({ page }) => {
  291 |       await page.setViewportSize({ width: 768, height: 1024 });
  292 |       await page.goto('/');
  293 |       await page.waitForLoadState('domcontentloaded');
  294 |       const hasOverflow = await page.evaluate(() => {
  295 |         return document.documentElement.scrollWidth > window.innerWidth;
  296 |       });
  297 |       expect(hasOverflow).toBe(false);
  298 |     });
  299 | 
  300 |     test('no horizontal overflow on desktop', async ({ page }) => {
  301 |       await page.setViewportSize({ width: 1280, height: 720 });
  302 |       await page.goto('/');
  303 |       await page.waitForLoadState('domcontentloaded');
  304 |       const hasOverflow = await page.evaluate(() => {
  305 |         return document.documentElement.scrollWidth > window.innerWidth;
  306 |       });
  307 |       expect(hasOverflow).toBe(false);
  308 |     });
  309 | 
  310 |     test('dashboard is responsive on mobile', async ({ page }) => {
  311 |       await page.setViewportSize({ width: 375, height: 812 });
  312 |       await loginAsCreator(page);
  313 |       await page.goto('/user/dashboard');
  314 |       await page.waitForLoadState('domcontentloaded');
  315 |       await expect(page.locator('body')).toBeVisible();
  316 |       const hasOverflow = await page.evaluate(() => {
  317 |         return document.documentElement.scrollWidth > window.innerWidth;
  318 |       });
  319 |       expect(hasOverflow).toBe(false);
  320 |     });
  321 |   });
  322 | 
  323 |   test.describe('CSS/JS Asset Verification', () => {
  324 |     test('production CSS and JS bundles load', async ({ page }) => {
  325 |       const loadedAssets: { url: string; type: string }[] = [];
  326 |       const failedAssets: { url: string; type: string }[] = [];
  327 | 
  328 |       page.on('response', async response => {
  329 |         const url = response.url();
  330 |         if (url.includes('/build/')) {
  331 |           const ext = url.split('.').pop()?.split('?')[0];
  332 |           if (ext === 'css') {
  333 |             if (response.status() === 200) loadedAssets.push({ url, type: 'css' });
  334 |             else failedAssets.push({ url, type: 'css' });
  335 |           } else if (ext === 'js') {
  336 |             if (response.status() === 200) loadedAssets.push({ url, type: 'js' });
  337 |             else failedAssets.push({ url, type: 'js' });
  338 |           }
  339 |         }
  340 |       });
  341 | 
  342 |       await page.goto('/');
  343 |       await page.waitForLoadState('domcontentloaded');
  344 | 
  345 |       console.log('=== LOADED BUILD ASSETS ===');
  346 |       loadedAssets.forEach(a => console.log(`${a.type}: ${a.url}`));
  347 |       console.log('=== FAILED BUILD ASSETS ===');
  348 |       failedAssets.forEach(a => console.log(`${a.type}: ${a.url}`));
  349 | 
  350 |       expect(failedAssets.length).toBe(0);
> 351 |       expect(loadedAssets.length).toBeGreaterThan(0);
      |                                   ^ Error: expect(received).toBeGreaterThan(expected)
  352 |     });
  353 |   });
  354 | });
  355 | 
```